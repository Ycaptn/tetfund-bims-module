<?php

namespace TETFund\BIMSOnboarding\Listeners;

use TETFund\BIMSOnboarding\Models\BIMSRecord;
use TETFund\BIMSOnboarding\Models\BIMSRecordUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BIMSRecordUpdatedListener
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
     * @param  BIMSRecordUpdated  $event
     * @return void
     */
    public function handle(BIMSRecordUpdated $event)
    {
        //
    }
}
