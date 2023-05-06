<?php

namespace TETFund\BIMSOnboarding\Events;

use TETFund\BIMSOnboarding\Models\BIMSRecord;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BIMSRecordCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bIMSRecord;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(BIMSRecord $bIMSRecord)
    {
        $this->bIMSRecord = $bIMSRecord;
    }

}
