<?php

declare(strict_types=1);

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')->id;

        return [
            'product_category_id' => ['nullable', 'integer', 'exists:product_categories,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'generic_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'barcode' => [
                'nullable', 
                'string', 
                'max:255',
                Rule::unique('products', 'barcode')
                    ->where(function ($query) {
                        return $query->where('pharmacy_id', $this->user()->pharmacy_id);
                    })
                    ->ignore($productId),
            ],
            'requires_prescription' => ['boolean'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'reorder_level' => ['integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'barcode.unique' => 'This barcode is already in use for another product.',
        ];
    }
}