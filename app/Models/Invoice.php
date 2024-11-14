<?php

namespace App\Models;

use App\Observers\InvoiceObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([InvoiceObserver::class])]
class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'client_name',
        'client_address',
        'remarks',
        'discount_amount',
        'subtotal',
        'gst_amount',
        'grand_total',
    ];

    /**
     * Get all of the items for the Invoice
     *
     */
    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id');
    }
}
