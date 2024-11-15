<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('/invoices', InvoiceController::class)->parameters([
        'invoice' => 'id'
    ]);

    Route::get('/download-invoice/{id}', [InvoiceController::class, 'download']);

    Route::apiResource('/invoice-items', InvoiceItemController::class)->parameters([
        'invoice-items' => 'id'
    ]);
});
