<?php

declare(strict_types=1);

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fulfillment_type' => ['required', 'string', 'in:pickup,delivery'],
            'delivery_address' => ['required_if:fulfillment_type,delivery', 'nullable', 'string', 'max:500'],
        ];
    }
}
