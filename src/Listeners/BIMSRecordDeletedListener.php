<?php

namespace TETFund\BIMSOnboarding\Listeners;

use TETFund\BIMSOnboarding\Models\BIMSRecord;
use TETFund\BIMSOnboarding\Models\BIMSRecordDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
        //
    }
}
