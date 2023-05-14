<?php

namespace TETFund\BIMSOnboarding\Listeners;

use TETFund\BIMSOnboarding\Models\BIMSRecord;
use TETFund\BIMSOnboarding\Events\BIMSRecordCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use TETFund\BIMSOnboarding\Notifications\BIMSRecordVerificationNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class BIMSRecordCreatedListener implements ShouldQueue
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
        $bIMSRecord = $event->bIMSRecord;
        if( !is_null($bIMSRecord) && !empty($bIMSRecord) )
        {
            $validator = Validator::make(
                ['email_imported'=>  $bIMSRecord->email_imported ], 
                ['email_imported' => 'required|string|email|max:300',]
            );
     
            if (!$validator->fails()){
                Notification::route('mail', [
                    $bIMSRecord->email_imported => $bIMSRecord->first_name_imported,
                ])->notify( new BIMSRecordVerificationNotification($bIMSRecord));
            }
           
        }
        
    }
}
