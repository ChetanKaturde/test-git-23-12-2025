<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Material;
use App\Exports\TransactionsExport;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use PDF;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    private const MAX_EXCEL_EXPORT_RECORDS = 50000;
    private const MAX_PDF_EXPORT_RECORDS = 5000;
    private const DEFAULT_PAGINATION_SIZE = 20;

    /**
     * Display the reports index page with filtered transactions
     */
    public function index(Request $request)
    {
        // Validate request parameters
        $validator = $this->validateFilters($request);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Cache materials list for 1 hour since it doesn't change frequently
        $materials = Cache::remember('materials.all', 3600, function () {
            return Material::select('id', 'name')
                          ->orderBy('name')
                          ->get();
        });

        $transactions = $this->getFilteredTransactions($request)
                            ->paginate(self::DEFAULT_PAGINATION_SIZE)
                            ->withQueryString();

        return view('reports.index', compact('transactions', 'materials'));
    }

    /**
     * Export transactions to Excel format
     */
    public function exportExcel(Request $request)
    {
        $validator = $this->validateFilters($request);
        
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            // Check record count before export
            $recordCount = $this->getFilteredTransactions($request)->count();
            
            if ($recordCount > self::MAX_EXCEL_EXPORT_RECORDS) {
                return back()->with('error', "Too many records to export ({$recordCount}). Please narrow your filters.");
            }

            $filename = 'inventory_transactions_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
            
            return Excel::download(new TransactionsExport($request), $filename);
            
        } catch (\Exception $e) {
            Log::error('Excel export failed', [
                'error' => $e->getMessage(),
                'filters' => $request->all()
            ]);
            
            return back()->with('error', 'Failed to export Excel file. Please try again or contact support.');
        }
    }

    public function exportPDF(Request $request)
    {
        $validator = $this->validateFilters($request);
        
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $query = $this->getFilteredTransactions($request);
            $recordCount = $query->count();
            
            if ($recordCount > self::MAX_PDF_EXPORT_RECORDS) {
                return back()->with('error', "Too many records for PDF export ({$recordCount}). Please use Excel export or narrow your filters.");
            }

            $transactions = $query->get();
            $filters = $this->getFilterLabels($request);
            
            if ($transactions->isEmpty()) {
                return back()->with('error', 'No transactions found for the selected filters.');
            }
            
            $pdf = PDF::loadView('reports.pdf', compact('transactions', 'filters'))
                     ->setPaper('a4', 'landscape')
                     ->setOptions([
                         'isHtml5ParserEnabled' => true,
                         'isRemoteEnabled' => false
                     ]);
            
            $filename = 'inventory_transactions_' . now()->format('Y-m-d_H-i-s') . '.pdf';
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            Log::error('PDF export failed', [
                'error' => $e->getMessage(),
                'filters' => $request->all()
            ]);
            
            return back()->with('error', 'Failed to export PDF file. Please try again or contact support.');
        }
    }

    /**
     * Get filtered transactions query with optimizations
     */
  private function getFilteredTransactions(Request $request): Builder
{
    $cacheKey = 'filtered_transactions_' . md5(serialize($request->all()));
    
    return Cache::remember($cacheKey, 300, function () use ($request) {
        $query = InventoryTransaction::query()
            ->select([
                'inventory_transactions.*',
                'materials.name as material_name',
                'inventory_batches.batch_number'
            ])
            ->join('inventory_batches', 'inventory_transactions.batch_id', '=', 'inventory_batches.id')
            ->join('materials', 'inventory_batches.material_id', '=', 'materials.id');

        $this->applyDateFilter($query, $request);
        $this->applyMaterialFilter($query, $request);
        $this->applyTransactionTypeFilter($query, $request);

        return $query->latest('inventory_transactions.transaction_date');
    });
}


    /**
     * Apply date range filter
     */
    private function applyDateFilter(Builder $query, Request $request): void
    {
        if ($request->filled(['from_date', 'to_date'])) {
            try {
                $fromDate = Carbon::parse($request->from_date)->startOfDay();
                $toDate = Carbon::parse($request->to_date)->endOfDay();
                
                // Validate date range
                if ($fromDate->gt($toDate)) {
                    throw new \InvalidArgumentException('From date cannot be after to date');
                }
                
                $query->whereBetween('inventory_transactions.transaction_date', [$fromDate, $toDate]);
                
            } catch (\Exception $e) {
                Log::warning('Invalid date filter applied', [
                    'from_date' => $request->from_date,
                    'to_date' => $request->to_date,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Apply material filter
     */
    private function applyMaterialFilter(Builder $query, Request $request): void
    {
        if ($request->filled('material_id')) {
            $query->where('materials.id', '=', $request->material_id);
        }
    }
private function applyTransactionTypeFilter(Builder $query, Request $request): void
{
    $validTypes = ['intake', 'dispatch'];

    if ($request->filled('type') && in_array($request->type, $validTypes)) {
        $query->where('inventory_transactions.type', '=', $request->type);
    }
}


    /**
     * Get filter labels for display/export
     */
    private function getFilterLabels(Request $request): array
    {
        $filters = [];

        if ($request->filled(['from_date', 'to_date'])) {
            $filters['date_range'] = $request->from_date . ' to ' . $request->to_date;
        }

        if ($request->filled('material_id')) {
            $material = Cache::remember(
                "material.{$request->material_id}",
                3600,
                fn() => Material::find($request->material_id)
            );
            
            $filters['material'] = $material?->name ?? 'Unknown Material';
        }

        if ($request->filled('type')) {
            $filters['type'] = ucfirst($request->type);
        }

        return $filters;
    }

    private function validateFilters(Request $request): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            'from_date' => 'nullable|date|before_or_equal:to_date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'material_id' => 'nullable|integer|exists:materials,id',
            'type' => 'nullable|string|in:intake,dispatch',
        ]);
    }
}