<?php

namespace App\Exports;

use App\Models\InventoryTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;  // <-- ADD THIS
use Illuminate\Http\Request;

class TransactionsExport implements FromCollection, WithHeadings  // <-- ADD WithHeadings here
{
    protected $request;
  
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function headings(): array
    {
        return [
            'ID', 'Batch', 'Type', 'Quantity', 'Weight', 'Value', 'Transaction Date'
        ];
    }

    public function collection()
    {
        $query = InventoryTransaction::query()->with('batch.material');

        if ($this->request->filled('from_date') && $this->request->filled('to_date')) {
            $query->whereBetween('transaction_date', [
                $this->request->from_date,
                $this->request->to_date
            ]);
        }

        if ($this->request->filled('material_id')) {
            $query->whereHas('batch', function($q) {
                $q->where('material_id', $this->request->material_id);
            });
        }

        if ($this->request->filled('type')) {
            $query->where('type', $this->request->type);
        }

        return $query->get()->map(function($item) {
            return [
                $item->id,
                $item->batch->material->name ?? 'N/A',
                ucfirst($item->type),
                $item->quantity,
                $item->weight,
                $item->total_value,
                $item->transaction_date->format('Y-m-d'),
            ];
        });
    }
}
