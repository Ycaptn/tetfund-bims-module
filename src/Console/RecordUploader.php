<?php

namespace TETFund\BIMSOnboarding\Console;

use PDF;
use File;
use Flash;
use Session;
use Validator;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Config;

use Illuminate\Console\Command;

use App\Models\Beneficiary;
use App\Models\BeneficiaryMember;

use TETFund\BIMSOnboarding\Models\BIMSRecord;
use Hasob\FoundationCore\Models\Organization;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class RecordUploader extends Command
{
    protected $signature = 'bims:csv-uploader {beneficiary_id} {record_type} {record_csv_file_path}';
    protected $description = 'Uploads a BIMS record from a local CSV file for a beneficiary institution';

    public function __construct(){
        parent::__construct();
    }

    public function handle(){
    
        $record_type = $this->argument('record_type');
        $beneficiary_id = $this->argument('beneficiary_id');
        $record_csv_file_path = $this->argument('record_csv_file_path');

        $beneficiary = Beneficiary::find($beneficiary_id);
        if ($beneficiary == null){
            echo "Beneficiary not found.\n";
            return 0;
        }

        echo "Running BIMS CSV Uploader for {$beneficiary_id} of type {$record_type} on file {$record_csv_file_path}\n";

        $loop = 1;
        $created_records_counter = 0;
        $duplicated_records_counter = 0;
        $duplicated_records="";

        $errors = [];
         
        //Process each line
        if (($handle = fopen($record_csv_file_path, "r")) !== FALSE) {
            while (false !== ($data = fgetcsv($handle, 1024))) {
                
                $first_name = trim($data[0]);
                $middle_name = trim($data[1]);
                $last_name = trim($data[2]);
                $email = strtolower(trim($data[3]));
                $phone = trim($data[4]);
                
                $staff_code = trim($data[5]);
                $matric_code = trim($data[5]);

                $csv_headings = ["First Name", "Middle Name", 'Last Name', 'Email Address', 'Phone Number', 'Matric Code', 'Staff Code'];

                // skip csv headings 
                if(in_array($first_name, $csv_headings)
                    || in_array($middle_name, $csv_headings)
                    || in_array($last_name, $csv_headings)
                    || in_array($email, $csv_headings)
                    || in_array($phone, $csv_headings)
                    || in_array($staff_code, $csv_headings)
                    || in_array($matric_code, $csv_headings)
                    && $loop == 1
                )
                continue;
                
                $valid_email = null;
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $valid_email = $email;
                }

                // make adjustment for 10 digit phone number without initial 0

                $valid_telephone = null;
                if(strlen($phone)==10 && is_numeric($phone))
                    $valid_telephone = '0'. $phone;
                    
                if (strlen($phone)==11){
                    $valid_telephone=$phone;
                }elseif (strlen($phone)>11){
                    $phone_parts = explode(",",$phone);
                    foreach($phone_parts as $phone_part){
                        $phone_part = str_replace("+234","0",$phone_part);
                        $phone_part = str_replace("+234(0)","0",$phone_part);
                        $phone_part = str_replace("+234-(0)","0",$phone_part);
                        $phone_part = str_replace("-","",$phone_part);
                        $phone_part = str_replace("+","",$phone_part);
                        $phone_part = str_replace(" ","",$phone_part);

                        if (strlen($phone_part)==11){
                            $valid_telephone=$phone_part;
                            break;
                        }
                    }
                }

                //Check if the record is being duplicated
                //if the matric number exists, or if the phone exists, and if the email address exists

                // code optimization using query builder
                $query = BIMSRecord::where('beneficiary_id', optional($beneficiary_member)->beneficiary_id);

                if (!empty($email)) {
                    $query->where(function (Builder $query) use ($email) {
                        $query->where('email_imported', $email)
                            ->orWhere('email_verified', $email);
                    });
                }
 
                if (!empty($valid_telephone)) {
                    $query->where(function (Builder $query) use ($valid_telephone) {
                        $query->where('phone_imported', $valid_telephone)
                            ->orWhere('phone_verified', $valid_telephone);
                    });
                }

                if (!empty($staff_code) && str_contains($request->user_type, "academic")) {
                    $query->where(function (Builder $query) use ($staff_code) {
                        $query->where('staff_number_imported', $staff_code)
                            ->orWhere('staff_number_verified', $staff_code);
                    });
                }

                if (!empty($matric_code) && str_contains($request->user_type, "student")) {
                    $query->where(function (Builder $query) use ($matric_code) {
                        $query->where('matric_number_imported', $matric_code)
                            ->orWhere('matric_number_verified', $matric_code);
                    });
                }
 

                if ( 
                    $query->count() > 0
                    // (empty($email)==false && BIMSRecord::where('email_imported',$email)->where('beneficiary_id',optional($beneficiary)->id)->count()>0) || 
                    // (empty($valid_telephone)==false && BIMSRecord::where('phone_imported',$valid_telephone)->where('beneficiary_id',optional($beneficiary)->id)->count()>0) || 
                    // (empty($staff_code)==false && str_contains($record_type,"academic") && BIMSRecord::where('staff_number_imported',$staff_code)->where('beneficiary_id',optional($beneficiary)->id)->count()>0) || 
                    // (empty($matric_code)==false && str_contains($record_type,"student") && BIMSRecord::where('matric_number_imported',$matric_code)->where('beneficiary_id',optional($beneficiary)->id)->count()>0) || 
                    // (empty($email)==false && BIMSRecord::where('email_verified',$email)->where('beneficiary_id',optional($beneficiary)->id)->count()>0) || 
                    // (empty($valid_telephone)==false && BIMSRecord::where('phone_verified',$valid_telephone)->where('beneficiary_id',optional($beneficiary)->id)->count()>0) || 
                    // (empty($staff_code)==false && str_contains($record_type,"academic") && BIMSRecord::where('staff_number_verified',$staff_code)->where('beneficiary_id',optional($beneficiary)->id)->count()>0) || 
                    // (empty($matric_code)==false && str_contains($record_type,"student") && BIMSRecord::where('matric_number_verified',$matric_code)->where('beneficiary_id',optional($beneficiary)->id)->count()>0)
                )
                {

                    //duplicate record detected.
                    $duplicated_records_counter++;
                    echo "Duplicate Record = {$first_name} {$middle_name} {$last_name} {$email} {$valid_telephone} {$beneficiary->id}\n";

                } else {

                    try{

                        $bims_record =  BIMSRecord::create([
                            'organization_id'=>$beneficiary->organization_id,
                            'beneficiary_id'=>$beneficiary->id,
                            'first_name_imported'=>$first_name,
                            'middle_name_imported'=>$middle_name,
                            'last_name_imported'=>$last_name,
                            'phone_imported'=>$valid_telephone,
                            'email_imported'=>$email,
                            'staff_number_imported' => (str_contains($record_type,"academic") ? $staff_code : null),
                            'matric_number_imported' => (str_contains($record_type,"student") ? $matric_code : null),
                            'user_status' => 'new-import',
                            'user_type' => $record_type,
                        ]);
                        $created_records_counter++;
                        echo "{$created_records_counter} Record added for {$last_name}, {$first_name} - {$email} \n";

                    } catch (\Throwable $th) {
                        //throw $th;
                        \Log::error($th);
                    }

                }

                //Save the record.
                $loop++;
            }
        } else {
            echo "The uploaded file is empty \n";
        }
        
        if (count($errors) > 0) {
            echo "Errors processing file \n";
        }
        echo "{$created_records_counter} new records saved, {$duplicated_records_counter} duplicate records \n";


        echo "Done Uploading BIMS records \n";
        return 1;
    }

}
