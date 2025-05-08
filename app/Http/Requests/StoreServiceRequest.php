<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->isEmployee();
    }

    public function rules()
    {
        return [
            'customer_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'notes' => 'nullable|string|max:500',
        ];
    }
}