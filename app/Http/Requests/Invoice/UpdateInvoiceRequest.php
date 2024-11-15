<?php

namespace App\Http\Requests\Invoice;

use Carbon\Carbon;
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
            'invoice_date' => 'required|date',
            'client_name' => 'required|string',
            'client_address' => 'required|string',
            'remarks' => 'nullable|string',
            'subtotal' => 'required|numeric',

            'discount' => 'nullable|numeric|min:0|max:100',

            'gst_amount' => 'required|numeric',

            'grand_total' => 'required|numeric',

            'items' => 'required|array',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.item_quantity' => 'required|integer',
            'items.*.item_price' => 'required|numeric',
            'items.*.item_amount' => 'required|numeric'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Format the invoice_date to Y-m-d
        if ($this->has('invoice_date')) {
            $this->merge([
                'invoice_date' => Carbon::parse($this->invoice_date)->format('Y-m-d')
            ]);
        }
    }
}
