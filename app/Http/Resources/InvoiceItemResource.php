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
            'item_name' => $this->item_name,
            'item_price' => $this->item_price,
            'item_quantity' => $this->item_quantity,
            'item_amount' => $this->item_amount,
        ];
    }
}
