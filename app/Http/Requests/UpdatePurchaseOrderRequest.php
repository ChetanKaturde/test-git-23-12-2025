<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseOrderRequest extends StorePurchaseOrderRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'status' => 'required|in:pending,approved,received,cancelled',
        ]);
    }
}
