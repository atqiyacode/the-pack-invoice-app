<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class InvoiceFilters extends QueryFilters
{
    protected array $allowedFilters = [
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

    protected array $columnSearch = [
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

    protected array $allowedSorts = [
        'created_at',

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
}
