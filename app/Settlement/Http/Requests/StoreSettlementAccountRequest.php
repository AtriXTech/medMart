<?php

declare(strict_types=1);

namespace App\Settlement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreSettlementAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bank_id' => ['required', 'integer', 'exists:banks,id'],
            'account_number' => ['required', 'string', 'digits:10'],
        ];
    }
}
