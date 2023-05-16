<?php

namespace TETFund\BIMSOnboarding\Listeners;

use TETFund\BIMSOnboarding\Models\BIMSRecord;
use TETFund\BIMSOnboarding\Events\BIMSRecordDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use TETFund\BIMSOnboarding\Jobs\RemoveRecordFromBIMS;

class BIMSRecordDeletedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BIMSRecordDeleted  $event
     * @return void
     */
    public function handle(BIMSRecordDeleted $event)
    {
        $bIMSRecord = $event->bIMSRecord;
        RemoveRecordFromBIMS::dispatch($bIMSRecord);
    }   
}
