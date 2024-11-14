<?php

namespace App\Observers;

use App\Events\InvoiceItemEvent;
use App\Models\InvoiceItem;

class InvoiceItemObserver
{
    /**
     * Dispatch events and log activities when the Invoice Item is created, updated, deleted, restored, or force deleted.
     *
     */
    protected function handleEventAndLogActivity(InvoiceItem $data): void
    {
        InvoiceItemEvent::dispatch($data);
        //
    }

    /**
     * Handle the InvoiceItem "created" event.
     */
    public function created(InvoiceItem $invoiceItem): void
    {
        $this->handleEventAndLogActivity($invoiceItem);
    }

    /**
     * Handle the InvoiceItem "updated" event.
     */
    public function updated(InvoiceItem $invoiceItem): void
    {
        $this->handleEventAndLogActivity($invoiceItem);
    }

    /**
     * Handle the InvoiceItem "deleted" event.
     */
    public function deleted(InvoiceItem $invoiceItem): void
    {
        $this->handleEventAndLogActivity($invoiceItem);
    }

    /**
     * Handle the InvoiceItem "restored" event.
     */
    public function restored(InvoiceItem $invoiceItem): void
    {
        $this->handleEventAndLogActivity($invoiceItem);
    }

    /**
     * Handle the InvoiceItem "force deleted" event.
     */
    public function forceDeleted(InvoiceItem $invoiceItem): void
    {
        $this->handleEventAndLogActivity($invoiceItem);
    }
}
