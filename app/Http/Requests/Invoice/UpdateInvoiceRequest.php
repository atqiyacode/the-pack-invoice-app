<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'invoice_date' => 'sometimes|date',
            'client_name' => 'sometimes|string',
            'client_address' => 'sometimes|string',
            'remarks' => 'nullable|string',
            'discount_amount' => 'nullable|numeric|min:0|max:100',
            'subtotal' => 'sometimes|numeric',
            'gst_amount' => 'sometimes|numeric',
            'grand_total' => 'sometimes|numeric',
            'items' => 'required|array',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.item_quantity' => 'required|integer',
            'items.*.item_price' => 'required|numeric',
            'items.*.item_amount' => 'required|numeric'
        ];
    }
}
