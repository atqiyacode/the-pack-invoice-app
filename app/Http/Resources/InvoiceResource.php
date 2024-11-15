<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,

            'invoice_date' => $this->invoice_date,

            'invoice_date_formatted' => Carbon::parse($this->invoice_date)->setTimezone(config('app.timezone'))->isoFormat('LL'),

            'client_name' => $this->client_name,
            'client_address' => $this->client_address,
            'remarks' => $this->remarks,

            'subtotal' => (float) $this->subtotal,

            'discount' => (float) $this->discount,
            'discount_amount' => (float) $this->discount_amount,

            'gst' => (float) $this->gst,
            'gst_amount' => (float) $this->gst_amount,

            'grand_total' => (float) $this->grand_total,

            'items_count' => (float) $this->items_count,

            'items' => $this->when(
                request()->routeIs('invoices.*'),
                function () {
                    return InvoiceItemResource::collection($this->items);
                }
            ),
        ];
    }
}
