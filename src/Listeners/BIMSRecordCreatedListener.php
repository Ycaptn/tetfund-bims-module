<?php

namespace TETFund\BIMSOnboarding\Listeners;

use TETFund\BIMSOnboarding\Models\BIMSRecord;
use TETFund\BIMSOnboarding\Models\BIMSRecordCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BIMSRecordCreatedListener
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
     * @param  BIMSRecordCreated  $event
     * @return void
     */
    public function handle(BIMSRecordCreated $event)
    {
        //
    }
}
