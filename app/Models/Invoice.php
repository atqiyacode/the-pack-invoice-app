<?php

namespace App\Models;

use App\Filters\InvoiceFilters;
use App\Observers\InvoiceObserver;
use Carbon\Carbon;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[ObservedBy([InvoiceObserver::class])]
class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory;

    use Filterable;

    protected string $default_filters = InvoiceFilters::class;

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

        'discount',
        'gst',
    ];

    protected $casts = [
        'invoice_date' => 'date:Y-m-d',
    ];

    /**
     * Get all of the items for the Invoice
     *
     */
    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id');
    }


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->invoice_number = self::generateInvoiceNumber();
        });
    }

    protected static function generateInvoiceNumber()
    {
        $datePrefix = Carbon::now()->format('ymd'); // Format: YYMMDD

        // Find the last entry with the same date prefix
        $lastRecord = self::where('invoice_number', 'like', 'INV' . $datePrefix . '%')
            ->latest('invoice_number')
            ->first();

        // Extract the last sequence number and increment it
        if ($lastRecord) {
            $lastSequence = (int) Str::substr($lastRecord->invoice_number, -2); // Extract last two digits
            $newSequence = str_pad($lastSequence + 1, 2, '0', STR_PAD_LEFT); // Increment and pad to 2 digits
        } else {
            $newSequence = '01'; // Start from 01 if no record exists for today
        }

        return 'INV' . $datePrefix . $newSequence;
    }
}
