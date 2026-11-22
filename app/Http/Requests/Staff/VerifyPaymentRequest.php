<?php

declare(strict_types=1);

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class VerifyPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference' => ['required_without:orderReference', 'nullable', 'string', 'max:255'],
            'orderReference' => ['nullable', 'string', 'max:255'],
        ];
    }
}