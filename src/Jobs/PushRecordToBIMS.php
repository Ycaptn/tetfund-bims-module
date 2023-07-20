<?php

namespace TETFund\BIMSOnboarding\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use TETFund\BIMSOnboarding\Models\BIMSRecord;

class PushRecordToBIMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $bIMSRecord;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(BIMSRecord $bIMSRecord)
    {
        $this->bIMSRecord = $bIMSRecord;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(is_null($this->bIMSRecord) || !$this->bIMSRecord->is_verified)
        return;

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => env('BIMS_API_BASE_URL', 'https://bims.tetfund.gov.ng/api'). '/auth/register',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
            'client_id' => env('BIMS_CLIENT_ID', 'your client id'),
            'institution_id' => $this->bIMSRecord->beneficiary->bims_tetfund_id,
            'unique_id' => $this->bIMSRecord->id,
            'first_name' => $this->bIMSRecord->first_name_verified,
            'last_name' => $this->bIMSRecord->last_name_verified,
            'email' => $this->bIMSRecord->email_verified,
            'phone' => $this->bIMSRecord->phone_verified,
            'gender' => $this->bIMSRecord->gender_verified,
            'type' => $this->bIMSRecord->user_type == 'student' ? "STUDENT" : "LECTURER",
        ),
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
          ),
        ));


        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response, true);

        $status = $response['status']?? false;
        $title = $response['title']?? '';
        $message = $response['message']?? '';
        $errors = $response['errors'] ?? [];
        $data = $response['data'] ?? [];
        
        if ($status== true || $status == 1)
        {
            $this->activateBIMRecord();
        }
        else {
            if(
                isset($response['errors']['emails'])
                || isset($response['errors']['phone'])
            )
            {
                $response['report'] = $this->bIMSRecord->beneficiary->short_name." record with the email address ".$this->bIMSRecord->email_verified." pushed to bims failed";
                \Log::error($response);
            }
            else {
                $this->activateBIMRecord();
            }
           
        }
    }

    /**
     * Attempt to activate BIM Record 
     * 
     * @param string|array|null $err
     * @param string $msg
     */
    public function activateBIMRecord(){
        $this->bIMSRecord->user_status = 'bims-active';
        $this->bIMSRecord->save();        
    }    
}
