<?php

namespace App\Observers;

use App\Models\PackingPerformance;
use Illuminate\Support\Facades\Auth;

class PackingPerformanceObserver
{
    /**
     * Handle the PackingPerformance "created" event.
     */
    public function created(PackingPerformance $packingPerformance): void
    {
        //
    }

    public function creating(PackingPerformance $packingPerformance): void
    {
        if(Auth::check()) $packingPerformance->admin_id = Auth::user()->id;
    }

    /**
     * Handle the PackingPerformance "updated" event.
     */
    public function updated(PackingPerformance $packingPerformance): void
    {
        //
    }

    public function updating(PackingPerformance $packingPerformance): void
    {
        if(Auth::check()) $packingPerformance->admin_id = Auth::user()->id;
    }

    /**
     * Handle the PackingPerformance "deleted" event.
     */
    public function deleted(PackingPerformance $packingPerformance): void
    {
        //
    }

    /**
     * Handle the PackingPerformance "restored" event.
     */
    public function restored(PackingPerformance $packingPerformance): void
    {
        //
    }

    /**
     * Handle the PackingPerformance "force deleted" event.
     */
    public function forceDeleted(PackingPerformance $packingPerformance): void
    {
        //
    }
}
