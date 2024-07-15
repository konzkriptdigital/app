<?php

namespace App\Observers;

use App\Models\VerificationCode;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class VerificationCodeObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the VerificationCode "created" event.
     */
    public function created(VerificationCode $verificationCode): void
    {
        //
    }

    /**
     * Handle the VerificationCode "updated" event.
     */
    public function updated(VerificationCode $verificationCode): void
    {
        //
    }

    /**
     * Handle the VerificationCode "deleted" event.
     */
    public function deleted(VerificationCode $verificationCode): void
    {
        //
    }

    /**
     * Handle the VerificationCode "restored" event.
     */
    public function restored(VerificationCode $verificationCode): void
    {
        //
    }

    /**
     * Handle the VerificationCode "force deleted" event.
     */
    public function forceDeleted(VerificationCode $verificationCode): void
    {
        //
    }
}
