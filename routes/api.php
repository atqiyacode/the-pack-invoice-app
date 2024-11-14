<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('/invoices', InvoiceController::class)->parameters([
        'invoice' => 'id'
    ]);

    Route::apiResource('/invoice-items', InvoiceItemController::class)->parameters([
        'invoice-items' => 'id'
    ]);
});
