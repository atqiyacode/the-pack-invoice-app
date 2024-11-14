<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceItem\StoreInvoiceItemRequest;
use App\Http\Requests\InvoiceItem\UpdateInvoiceItemRequest;
use App\Http\Resources\InvoiceItemResource;
use App\Models\InvoiceItem;

class InvoiceItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoiceItems = InvoiceItem::paginate(15);
        return InvoiceItemResource::collection($invoiceItems);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceItemRequest $request)
    {
        $invoiceItem = new InvoiceItem();
        $invoiceItem->create($request->all());
        return $this->respondWithSuccess($invoiceItem);
    }

    /**
     * Display the specified resource.
     */
    public function show(InvoiceItem $invoiceItem)
    {
        return $this->respondWithSuccess($invoiceItem);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceItemRequest $request, InvoiceItem $invoiceItem)
    {
        $invoiceItem->update($request->all());
        return $this->respondWithSuccess($invoiceItem);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvoiceItem $invoiceItem)
    {
        $invoiceItem->delete();
        return $this->respondWithSuccess($invoiceItem);
    }
}
