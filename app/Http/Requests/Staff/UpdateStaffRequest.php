<?php

declare(strict_types=1);

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:users,email,' . $this->route('user')->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['sometimes', Password::min(8)],
            'staff_role_id' => ['sometimes', 'integer', 'exists:roles,id'],
        ];
    }
}