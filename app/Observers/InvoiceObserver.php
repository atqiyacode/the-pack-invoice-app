<?php

namespace App\Observers;

use App\Events\InvoiceEvent;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;

class InvoiceObserver
{
    /**
     * Dispatch events and log activities when the Invoice is created, updated, deleted, restored, or force deleted.
     *
     */
    protected function handleEventAndLogActivity($data): void
    {
        Log::info($data);
        InvoiceEvent::dispatch($data);
        //
    }

    /**
     * Handle the Invoice "created" event.
     */
    public function created(Invoice $invoice): void
    {
        $this->handleEventAndLogActivity($invoice);
    }

    /**
     * Handle the Invoice "updated" event.
     */
    public function updated(Invoice $invoice): void
    {
        $this->handleEventAndLogActivity($invoice);
    }

    /**
     * Handle the Invoice "deleted" event.
     */
    public function deleted(Invoice $invoice): void
    {
        $this->handleEventAndLogActivity($invoice);
    }

    /**
     * Handle the Invoice "restored" event.
     */
    public function restored(Invoice $invoice): void
    {
        $this->handleEventAndLogActivity($invoice);
    }

    /**
     * Handle the Invoice "force deleted" event.
     */
    public function forceDeleted(Invoice $invoice): void
    {
        $this->handleEventAndLogActivity($invoice);
    }
}
