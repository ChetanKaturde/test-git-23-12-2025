<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->canCreateInModule('materials');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'item_type' => 'required|in:good,service',
            'hsn_code' => 'nullable|string|max:20',
            'code' => 'nullable|string|max:50|alpha_num',
            'description' => 'nullable|string|max:500',
            'unit' => 'required|string|max:20',
            'unit_price' => 'required|numeric|min:0',
            'gst_rate' => 'nullable|numeric|between:0,100',
            'category' => 'nullable|string|max:100',
            'business_id' => 'required|integer|exists:businesses,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Material name is required.',
            'item_type.required' => 'Please select item type (Good or Service).',
            'unit.required' => 'Unit of measurement is required.',
            'unit_price.required' => 'Unit price is required.',
            'unit_price.min' => 'Unit price must be greater than or equal to 0.',
            'business_id.required' => 'Business ID is required.',
            'business_id.exists' => 'Invalid business selected.',
        ];
    }

    protected function prepareForValidation()
    {
        // Auto-assign business_id
        $this->merge([
            'business_id' => auth()->user()->business_id,
            'is_active' => true
        ]);
    }
}