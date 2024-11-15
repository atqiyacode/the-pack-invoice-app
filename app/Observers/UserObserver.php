<?php

namespace App\Observers;

use App\Events\UserEvent;
use App\Models\User;

class UserObserver
{
    /**
     * Dispatch events and log activities when the User is created, updated, deleted, restored, or force deleted.
     *
     */
    protected function handleEventAndLogActivity($data): void
    {
        UserEvent::dispatch($data);
        //
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->handleEventAndLogActivity($user);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $this->handleEventAndLogActivity($user);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $this->handleEventAndLogActivity($user);
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        $this->handleEventAndLogActivity($user);
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        $this->handleEventAndLogActivity($user);
    }
}
