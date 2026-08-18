<?php

declare(strict_types=1);

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class ReceivePurchaseOrderItemsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.purchase_order_item_id' => ['required', 'integer', 'exists:purchase_order_items,id'],
            'items.*.batch_number' => ['required', 'string', 'max:255'],
            'items.*.expiry_date' => ['required', 'date', 'after:today'],
            'items.*.quantity_received' => ['required', 'integer', 'min:1'],
            'items.*.cost_price' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
