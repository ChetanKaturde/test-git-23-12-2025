<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMaterialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $businessId = auth()->user()->business_id;
        $materialId = $this->route('material')->id;

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('materials', 'name')->where(function ($query) use ($businessId) {
                    return $query->where('business_id', $businessId);
                })->ignore($materialId)
            ],
            'item_type' => ['required', Rule::in(['good', 'service'])],
            'hsn_code' => ['nullable', 'string', 'max:20'],
            'code' => [
                'nullable',
                'string',
                'max:50',
                'alpha_num',
                Rule::unique('materials', 'code')->where(function ($query) use ($businessId) {
                    return $query->where('business_id', $businessId);
                })->ignore($materialId)
            ],
            'description' => ['nullable', 'string', 'max:500'],
            'unit' => ['required', 'string', 'max:20'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'gst_rate' => ['nullable', 'numeric', 'between:0,100'],
            'category' => ['nullable', 'string', 'max:100'],
            'material_type' => ['nullable', 'string', 'max:50'],
            'material_form' => ['nullable', 'string', 'max:50'],
            'grade' => ['nullable', 'string', 'max:100'],
            'unit_of_order' => ['nullable', 'string', 'max:20'],
            'estimated_weight_per_piece' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'dimensions' => ['nullable', 'array'],
            'dimensions.length' => ['nullable', 'numeric', 'min:0'],
            'dimensions.width' => ['nullable', 'numeric', 'min:0'],
            'dimensions.height' => ['nullable', 'numeric', 'min:0'],
            'dimensions.diameter' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Material name is required.',
            'name.unique' => 'This material name already exists.',
            'item_type.required' => 'Item type is required.',
            'item_type.in' => 'Item type must be either good or service.',
            'unit.required' => 'Unit is required.',
            'unit_price.required' => 'Unit price is required.',
            'unit_price.numeric' => 'Unit price must be a number.',
            'unit_price.min' => 'Unit price cannot be negative.',
            'gst_rate.numeric' => 'GST rate must be a number.',
            'gst_rate.between' => 'GST rate must be between 0 and 100.',
        ];
    }
}