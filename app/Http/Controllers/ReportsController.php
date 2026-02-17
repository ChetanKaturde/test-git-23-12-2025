<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            
            // Business Owner (admin) always has access
            if (!$user->isAdmin() && !$user->hasPermission('view_reports')) {
                abort(403, 'Access Denied');
            }
            
            return $next($request);
        });
    }

    public function index()
    {
        return view('reports.index');
    }

    public function aging()
    {
        $businessId = auth()->user()->business_id;
        
        $invoices = Invoice::where('business_id', $businessId)
            ->where('status', '!=', 'paid')
            ->whereNotNull('due_date')
            ->get()
            ->map(function ($invoice) {
                $balance = $invoice->total_amount - ($invoice->totalPaid() ?? 0);
                if ($balance <= 0) return null;
                
                $daysOverdue = Carbon::now()->diffInDays($invoice->due_date, false);
                $invoice->days_overdue = $daysOverdue;
                $invoice->balance = $balance;
                
                if ($daysOverdue <= 0) {
                    $invoice->age_bucket = 'current';
                } elseif ($daysOverdue <= 30) {
                    $invoice->age_bucket = '0-30';
                } elseif ($daysOverdue <= 60) {
                    $invoice->age_bucket = '31-60';
                } elseif ($daysOverdue <= 90) {
                    $invoice->age_bucket = '61-90';
                } else {
                    $invoice->age_bucket = '90+';
                }
                
                return $invoice;
            })
            ->filter()
            ->groupBy('age_bucket');

        $totals = [
            'current' => $invoices->get('current', collect())->sum('balance'),
            '0-30' => $invoices->get('0-30', collect())->sum('balance'),
            '31-60' => $invoices->get('31-60', collect())->sum('balance'),
            '61-90' => $invoices->get('61-90', collect())->sum('balance'),
            '90+' => $invoices->get('90+', collect())->sum('balance'),
        ];

        return view('reports.aging', compact('invoices', 'totals'));
    }

    public function agingExport()
    {
        $businessId = auth()->user()->business_id;
        
        $invoices = Invoice::where('business_id', $businessId)
            ->where('status', '!=', 'paid')
            ->whereNotNull('due_date')
            ->get()
            ->map(function ($invoice) {
                $balance = $invoice->total_amount - ($invoice->totalPaid() ?? 0);
                if ($balance <= 0) return null;
                
                $daysOverdue = Carbon::now()->diffInDays($invoice->due_date, false);
                return [
                    'Customer' => $invoice->customer_name,
                    'Invoice Number' => $invoice->invoice_number,
                    'Due Date' => $invoice->due_date->format('Y-m-d'),
                    'Amount' => $balance,
                    'Days Overdue' => max(0, $daysOverdue)
                ];
            })
            ->filter()
            ->values();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="aging-report-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($invoices) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Customer', 'Invoice Number', 'Due Date', 'Amount', 'Days Overdue']);
            
            foreach ($invoices as $invoice) {
                fputcsv($file, $invoice);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function expenses()
    {
        $businessId = auth()->user()->business_id;

        // Get expense summary by category
        $expensesByCategory = Expense::where('business_id', $businessId)
            ->selectRaw('category, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('category')
            ->get()
            ->map(function ($item) {
                $item->category_display = $item->getCategoryDisplayName();
                return $item;
            });

        // Monthly expense trends for the last 12 months
        $monthlyExpenses = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyExpenses[] = [
                'month' => $date->format('M Y'),
                'total' => Expense::where('business_id', $businessId)
                    ->whereYear('expense_date', $date->year)
                    ->whereMonth('expense_date', $date->month)
                    ->sum('amount')
            ];
        }

        // Total expenses this year
        $totalExpensesThisYear = Expense::where('business_id', $businessId)
            ->whereYear('expense_date', now()->year)
            ->sum('amount');

        return view('reports.expenses', compact('expensesByCategory', 'monthlyExpenses', 'totalExpensesThisYear'));
    }

    public function profitLoss()
    {
        $businessId = auth()->user()->business_id;

        // Get data for the last 12 months
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);

            $revenue = Invoice::where('business_id', $businessId)
                ->where('status', 'paid')
                ->whereYear('issue_date', $date->year)
                ->whereMonth('issue_date', $date->month)
                ->sum('total_amount') ?? 0;

            $expenses = Expense::where('business_id', $businessId)
                ->whereYear('expense_date', $date->year)
                ->whereMonth('expense_date', $date->month)
                ->sum('amount') ?? 0;

            $data[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenue,
                'expenses' => $expenses,
                'profit' => $revenue - $expenses
            ];
        }

        // Current year totals
        $currentYear = now()->year;
        $yearRevenue = Invoice::where('business_id', $businessId)
            ->where('status', 'paid')
            ->whereYear('issue_date', $currentYear)
            ->sum('total_amount') ?? 0;

        $yearExpenses = Expense::where('business_id', $businessId)
            ->whereYear('expense_date', $currentYear)
            ->sum('amount') ?? 0;

        $yearProfit = $yearRevenue - $yearExpenses;

        return view('reports.profit-loss', compact('data', 'yearRevenue', 'yearExpenses', 'yearProfit'));
    }
}