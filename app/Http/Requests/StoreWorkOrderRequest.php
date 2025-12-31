<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->canCreateInModule('work_orders');
    }

    public function rules(): array
    {
        return [
            'machine_id' => 'required|exists:machines,id',
            'customer_id' => 'nullable|exists:customers,id',
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'quoted_rate' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'machine_id.required' => 'Please select a machine.',
            'product_name.required' => 'Product name is required.',
            'quantity.required' => 'Quantity is required.',
            'quantity.min' => 'Quantity must be at least 1.',
        ];
    }

    protected function prepareForValidation()
    {
        // Auto-assign business_id and generate WO number
        $this->merge([
            'business_id' => auth()->user()->business_id,
            'wo_number' => $this->generateWoNumber(),
            'status' => 'pending'
        ]);
    }

    private function generateWoNumber(): string
    {
        $businessId = auth()->user()->business_id;
        $lastWO = \App\Models\WorkOrder::where('business_id', $businessId)
            ->orderBy('created_at', 'desc')
            ->first();
        
        $nextNumber = 1;
        if ($lastWO && $lastWO->wo_number) {
            $parts = explode('-', $lastWO->wo_number);
            if (count($parts) >= 3) {
                $nextNumber = intval(end($parts)) + 1;
            }
        }
        
        return 'WO-' . now()->format('Y') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}