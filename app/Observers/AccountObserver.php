<?php

namespace App\Observers;

use App\Events\AccountCreated;
use App\Models\Account;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class AccountObserver
{
    /**
     * Handle the Account "created" event.
     */
    public function created(Account $account): void
    {
        AccountCreated::dispatch($account);
    }

    /**
     * Handle the Account "updated" event.
     */
    public function updated(Account $account): void
    {
        AccountCreated::dispatch($account);
    }

    /**
     * Handle the Account "deleted" event.
     */
    public function deleted(Account $account): void
    {
        //
    }

    /**
     * Handle the Account "restored" event.
     */
    public function restored(Account $account): void
    {
        //
    }

    /**
     * Handle the Account "force deleted" event.
     */
    public function forceDeleted(Account $account): void
    {
        //
    }
}
