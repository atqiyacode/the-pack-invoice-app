<?php

namespace App\Http\Controllers;

use App\Http\Requests\Invoice\StoreInvoiceRequest;
use App\Http\Requests\Invoice\UpdateInvoiceRequest;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->merge([
            'sorts' => '-invoice_number'
        ]);
        $invoices = Invoice::with(['items'])->withCount(['items'])->useFilters()->dynamicPaginate();
        return InvoiceResource::collection($invoices);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request)
    {
        $invoice = new Invoice($request->all());
        $invoice->save();

        // collect items
        $items = collect($request->items)->map(function ($item) use ($invoice) {
            $data = [
                'invoice_id' => $invoice->id,
                'item_name' => $item['item_name'],
                'item_quantity' => $item['item_quantity'],
                'item_price' => $item['item_price'],
                'item_amount' => $item['item_amount']
            ];
            return new InvoiceItem($data);
        });

        // save many items relation
        $invoice->items()->saveMany($items);

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

        // collect items
        $items = collect($request->items)->map(function ($item) use ($invoice) {
            return [
                'id' => $item['id'] ?? null, // Gunakan ID jika tersedia
                'invoice_id' => $invoice->id,
                'item_name' => $item['item_name'],
                'item_quantity' => $item['item_quantity'],
                'item_price' => $item['item_price'],
                'item_amount' => $item['item_amount']
            ];
        })->toArray();

        // Invoice items `upsert`
        InvoiceItem::upsert($items, ['id'], ['item_name', 'item_quantity', 'item_price', 'item_amount']);

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

    public function download($id)
    {
        $invoice = Invoice::with(['items'])->findOrFail($id);
        // Generate the PDF
        $pdf = Pdf::loadView('invoice.generate-pdf', [
            'invoice' => $invoice
        ])
            ->setPaper('A4', 'landscape');

        // Generate the file name
        $fileName = Str::upper(Str::slug($invoice->invoice_number)) . '.pdf';

        // Return the PDF as a download
        return response()->streamDownload(
            fn() => print($pdf->output()),
            $fileName
        );
    }
}
