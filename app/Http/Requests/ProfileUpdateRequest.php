<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        $rules = [];
        
        // Personal info - all users can edit
        $rules['name'] = ['required', 'string', 'max:255'];
        $rules['email'] = ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)];
        $rules['phone'] = ['required', 'string', 'regex:/^[6-9][0-9]{9}$/', 'size:10'];
        
        // Business address - only admin can edit
        if ($user->isAdmin()) {
            $rules['company_address'] = ['nullable', 'string', 'max:500'];
            $rules['company_city'] = ['nullable', 'string', 'max:100'];
            $rules['company_state'] = ['nullable', 'string', 'max:100'];
            $rules['company_pincode'] = ['nullable', 'string', 'regex:/^[0-9]{6}$/', 'size:6'];
            $rules['company_country'] = ['nullable', 'string', 'max:100'];
            $rules['warehouse_address'] = ['nullable', 'string', 'max:500'];
            $rules['warehouse_city'] = ['nullable', 'string', 'max:100'];
            $rules['warehouse_state'] = ['nullable', 'string', 'max:100'];
            $rules['warehouse_pincode'] = ['nullable', 'string', 'regex:/^[0-9]{6}$/', 'size:6'];
            $rules['warehouse_country'] = ['nullable', 'string', 'max:100'];
            $rules['warehouse_same_as_company'] = ['boolean'];
        }
        
        return $rules;
    }
}
