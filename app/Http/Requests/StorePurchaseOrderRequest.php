<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->canCreateInModule('purchase_orders');
    }

    public function rules(): array
    {
        return [
            'vendor_id' => 'required|exists:vendors,id',
            'po_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0.01',
            'items.*.gst_rate' => 'nullable|numeric|between:0,100',
            'items.*.description' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'vendor_id.required' => 'Please select a vendor.',
            'items.required' => 'At least one item is required.',
            'items.*.item_name.required' => 'Item name is required.',
            'items.*.quantity.required' => 'Quantity is required.',
            'items.*.quantity.min' => 'Quantity must be greater than 0.',
            'items.*.unit_price.required' => 'Unit price is required.',
            'items.*.unit_price.min' => 'Unit price must be greater than 0.',
        ];
    }

    protected function prepareForValidation()
    {
        // Auto-assign business_id
        $this->merge([
            'business_id' => auth()->user()->business_id
        ]);
    }
}