<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,
            'item_name' => $this->item_name,
            'item_price' => (float) $this->item_price,
            'item_quantity' => (float) $this->item_quantity,
            'item_amount' => (float) $this->item_amount,

            'invoice_id' => $this->when(
                request()->routeIs('invoice-items.*'),
                function () {
                    return $this->invoice_id;
                }
            ),

            'invoice' => $this->when(
                request()->routeIs('invoice-items.*'),
                function () {
                    return new InvoiceResource($this->invoice);
                }
            ),
        ];
    }
}
