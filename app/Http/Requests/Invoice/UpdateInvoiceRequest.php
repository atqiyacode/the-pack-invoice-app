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
            'discount_amount' => 'nullable|numeric|decimal:0,1',
            'subtotal' => 'sometimes|numeric',
            'gst_amount' => 'sometimes|numeric',
            'grand_total' => 'sometimes|numeric',
        ];
    }
}
