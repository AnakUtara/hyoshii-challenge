<?php

namespace App\Observers;

use App\Models\PersonInCharge;
use Illuminate\Support\Facades\Auth;

class PersonInChargeObserver
{
    /**
     * Handle the PersonInCharge "created" event.
     */
    public function created(PersonInCharge $personInCharge): void
    {
        //
    }

    public function creating(PersonInCharge $personInCharge): void
    {
        if(Auth::check()) $personInCharge->admin_id = Auth::user()->id;
    }

    /**
     * Handle the PersonInCharge "updated" event.
     */
    public function updated(PersonInCharge $personInCharge): void
    {
        //
    }

    public function updating(PersonInCharge $personInCharge): void
    {
        if(Auth::check()) $personInCharge->admin_id = Auth::user()->id;
    }

    /**
     * Handle the PersonInCharge "deleted" event.
     */
    public function deleted(PersonInCharge $personInCharge): void
    {
        //
    }

    /**
     * Handle the PersonInCharge "restored" event.
     */
    public function restored(PersonInCharge $personInCharge): void
    {
        //
    }

    /**
     * Handle the PersonInCharge "force deleted" event.
     */
    public function forceDeleted(PersonInCharge $personInCharge): void
    {
        //
    }
}
