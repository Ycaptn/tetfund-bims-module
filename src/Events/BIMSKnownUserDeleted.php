<?php

namespace TETFund\BIMSOnboarding\Events;

use TETFund\BIMSOnboarding\Models\BIMSKnownUser;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BIMSKnownUserDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bIMSKnownUser;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(BIMSKnownUser $bIMSKnownUser)
    {
        $this->bIMSKnownUser = $bIMSKnownUser;
    }

}
