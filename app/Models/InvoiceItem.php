<?php

namespace App\Models;

use App\Observers\InvoiceItemObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([InvoiceItemObserver::class])]
class InvoiceItem extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceItemFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_id',
        'item_name',
        'item_price',
        'item_quantity',
        'item_amount',
    ];

    /**
     * Get the invoice that owns the InvoiceItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }
}
