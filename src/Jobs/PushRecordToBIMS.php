<?php

namespace TETFund\BIMSOnboarding\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        CURLOPT_URL => env('BIMS_REGISTERATION_URI', 'https://bims.tetfund.gov.ng/api/auth/register'),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
            'client_id' => env('BIMS_CLIENT_ID', 'your client id'),
            'unique_id' => $this->bIMSRecord->id,
            'first_name' => $this->bIMSRecord->first_name_verified,
            'last_name' => $this->bIMSRecord->last_name_verified,
            'email' => $this->bIMSRecord->email_verifed,
            'phone' => $this->bIMSRecord->phone_number_verified,
            'gender' => $this->bIMSRecord->gender,
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
        
        // if ($status== true || $status == 1)
        $this->attemptBIMRecordActivation(null, $message);
      
    }

    /**
     * Attempt to activate BIM Record 
     * 
     * @param string|array|null $err
     * @param string $msg
     */
    public function attemptBIMRecordActivation($err = null, String $msg=null){

    
        $activateBIMSRecord = function (){
            $this->bIMSRecord->user_status = 'bims-active';
            $this->bIMSRecord->save();
        };
        $activateBIMSRecord();

        // if(is_null($err))
        // activateBIMSRecord();
        // else 
        //     checkErr($err);
        // if(is_array($err)){
        //     $err = flatten_array($err);
        //     $err = implode(',',$err);
        //     checkErr($err);
        // }

        // // check the error message content 
        // $checkErr = function ($err) {
        //     if(!str_contains($err, 'is taken')
        //         ||!str_contains($err, 'must be unique')
        //         ||!str_contains($err, 'must be') 
        //     )  
        //       activateBIMSRecord();
        //     else {
        //         // notify the user of the error message 
        //         echo $msg;
        //     }
        // };
           
    }

    /**
     * Flattens a multidimensional array into a single-dimensional array.
     *
     * @param array $array The multidimensional array to be flattened.
     * @return array The flattened array containing all the values from the original array.
     */
    function flatten_array($array) {
        $result = [];
        foreach ($array as $value) {
            if (is_array($value)) {
                $result = [...$result, ...flatten_array($value)];
            } else {
                $result[] = $value;
            }
        }
        return $result;
    }
    
}
