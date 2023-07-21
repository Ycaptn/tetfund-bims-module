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
        if(is_null($this->bIMSRecord) || $this->bIMSRecord->user_status == 'bims-active')
        return;

        $curl = curl_init();
        $genders = ['M', 'F'];
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
            'first_name' => $this->bIMSRecord->first_name_imported,
            'last_name' => $this->bIMSRecord->last_name_imported,
            'email' => $this->bIMSRecord->email_imported,
            'phone' => $this->bIMSRecord->phone_imported,
            'gender' => $genders[array_rand($genders)], //$this->bIMSRecord->gender_imported,
            'type' => $this->bIMSRecord->user_type == 'student' ? "STUDENT" : "LECTURER",
        ),
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
          ),
        ));


        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if($error){
            \Log::error($error);
        }

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
            $email_taken = "The email has already been taken.";
            $phone_taken = "The phone has already been taken.";
            $errors = implode($this->arrayFlatten($errors));

            if(strstr($errors, $email_taken) || strstr($errors, $phone_taken)){
                $this->activateBIMRecord();
                return 1;
            }
            
            $response['report'] = $this->bIMSRecord->beneficiary->short_name." record with the email address ".$this->bIMSRecord->email_imported." pushed to bims failed";
            \Log::error($response);
            session(['push record to bims error' => $response]);
        }
    }

    /**
     * Attempt to activate BIM Record 
     * 
     * @param string|array|null $err
     * @param string $msg
     */
    protected function activateBIMRecord(){
        $this->bIMSRecord->user_status = 'bims-active';
        $this->bIMSRecord->save();        
    } 
    /**
     * Flatten an array
     */
    protected function arrayFlatten($array) {
        $flattened = array();
        foreach ($array as $element) {
            if (is_array($element)) {
                $flattened = array_merge($flattened, $this->arrayFlatten($element));
            } else {
                $flattened[] = $element;
            }
        }
        return $flattened;
    }
}
