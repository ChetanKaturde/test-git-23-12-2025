<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'work_order_id' => 'nullable|exists:work_orders,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20|regex:/^[\d\s\+\-\(\)]+$/',
            'customer_address' => 'required|string|max:1000',
            'customer_gstin' => 'nullable|string|max:15|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
            'invoice_date' => 'required|date|before_or_equal:today',
            'due_date' => 'nullable|date|after:invoice_date',
            'items' => 'required|array|min:1|max:50',
            'items.*.description' => 'required|string|max:500',
            'items.*.quantity' => 'required|numeric|min:0.01|max:999999',
            'items.*.unit_price' => 'required|numeric|min:0|max:999999',
            'items.*.tax_rate' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'customer_gstin.regex' => 'Invalid GSTIN format. Please enter a valid 15-digit GSTIN.',
            'customer_phone.regex' => 'Invalid phone number format.',
            'items.min' => 'At least one item is required.',
            'items.max' => 'Maximum 50 items allowed per invoice.',
        ];
    }
}