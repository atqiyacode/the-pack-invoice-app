<?php

namespace App\Http\Controllers;

use App\Http\Requests\Invoice\StoreInvoiceRequest;
use App\Http\Requests\Invoice\UpdateInvoiceRequest;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->merge([
            'sorts' => '-invoice_date'
        ]);
        $invoices = Invoice::with(['items'])->withCount(['items'])->useFilters()->dynamicPaginate();
        return InvoiceResource::collection($invoices);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request)
    {
        $invoice = new Invoice();
        $invoice->create($request->all());
        return $this->respondWithSuccess($invoice);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->loadCount('items');
        $result = new InvoiceResource($invoice);
        return $this->respondWithSuccess($result);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $invoice->update($request->all());
        $result = new InvoiceResource($invoice);
        return $this->respondWithSuccess($result);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return $this->respondWithSuccess($invoice);
    }
}
