<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
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
            'invoice_date' => 'required|date',
            'client_name' => 'required|string',
            'client_address' => 'required|string',
            'remarks' => 'nullable|string',
            'discount_amount' => 'nullable|numeric|decimal:0,1',
            'subtotal' => 'required|numeric',
            'gst_amount' => 'required|numeric',
            'grand_total' => 'required|numeric',
        ];
    }
}
