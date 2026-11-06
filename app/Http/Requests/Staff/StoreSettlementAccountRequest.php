<?php

declare(strict_types=1);

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class StoreSettlementAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bank_id' => ['required', 'integer', 'exists:banks,id'],
            'account_number' => ['required', 'string', 'max:10', 'min:10'],
            'account_name' => ['required', 'string', 'max:255'],
        ];
    }
}