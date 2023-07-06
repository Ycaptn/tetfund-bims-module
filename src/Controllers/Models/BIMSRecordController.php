<?php

namespace TETFund\BIMSOnboarding\Controllers\Models;

use App\Models\Beneficiary;
use App\Models\BeneficiaryMember;

use TETFund\BIMSOnboarding\Models\BIMSRecord;

use TETFund\BIMSOnboarding\Events\BIMSRecordCreated;
use TETFund\BIMSOnboarding\Events\BIMSRecordUpdated;
use TETFund\BIMSOnboarding\Events\BIMSRecordDeleted;

use TETFund\BIMSOnboarding\Requests\CreateBIMSRecordRequest;
use TETFund\BIMSOnboarding\Requests\UpdateBIMSRecordRequest;

use TETFund\BIMSOnboarding\DataTables\BIMSRecordDataTable;
use TETFund\BIMSOnboarding\DataTables\BIMSRecordReportDataTable;

use Hasob\FoundationCore\Controllers\BaseController;
use Hasob\FoundationCore\Models\Organization;
use TETFund\BIMSOnboarding\Jobs\PushRecordToBIMS;
use TETFund\BIMSOnboarding\Jobs\RemoveRecordFromBIMS;
use Illuminate\Database\Eloquent\Builder;

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

use Illuminate\Http\Request;
use Illuminate\Http\Response;


class BIMSRecordController extends BaseController
{

    public function index(Organization $org, BIMSRecordDataTable $bIMSRecordDataTable)
    {
        $current_user = Auth()->user();
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();

        $cdv_bims_records = new \Hasob\FoundationCore\View\Components\CardDataView(BIMSRecord::class, "tetfund-bims-module::pages.bims_records.card_view_item");
        $cdv_bims_records->setDataQuery(['organization_id'=>$org->id,'beneficiary_id'=>optional($beneficiary_member)->beneficiary_id])
                        ->addDataGroup('All','deleted_at',null)
                        ->addDataGroup('Academic Staff','user_type','academic')
                        ->addDataGroup('Non Academic','user_type','non-academic')
                        ->addDataGroup('Student','user_type','student')
                        ->addDataGroup('Others','user_type',null)
                        ->addDataGroup('Verified','is_verified', '1')
                        ->addDataGroup('Unverified','is_verified', '0')
                        ->setSearchFields([
                            "first_name_imported","middle_name_imported","last_name_imported","name_title_imported","name_suffix_imported","matric_number_imported","staff_number_imported","email_imported","phone_imported","phone_network_imported","bvn_imported","nin_imported",
                            "first_name_verified","middle_name_verified","last_name_verified","name_title_verified","name_suffix_verified","matric_number_verified","staff_number_verified","email_verified","phone_verified","phone_network_verified","bvn_verified","nin_verified",
                        ])->enableSearch(true)
                        ->enablePagination(true)
                        ->setPaginationLimit(30)
                        ->setSearchPlaceholder('Search BIMS Onboarding Records');

        if (request()->expectsJson()){
            return $cdv_bims_records->render();
        }

        return view('tetfund-bims-module::pages.bims_records.card_view_index')
                    ->with('current_user', $current_user)
                    ->with('beneficiary', optional($beneficiary_member)->beneficiary)
                    ->with('months_list', BaseController::monthsList())
                    ->with('states_list', BaseController::statesList())
                    ->with('cdv_bims_records', $cdv_bims_records);
    }

    public function displayBIMSRecordOnboarding(Organization $org)
    {
        $current_user = Auth()->user();
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();

        return view('tetfund-bims-module::pages.bims_records.onboard')
                    ->with('current_user', $current_user)
                    ->with('beneficiary', optional($beneficiary_member)->beneficiary)
                    ->with('months_list', BaseController::monthsList())
                    ->with('states_list', BaseController::statesList());
    }

    public function create(Organization $org)
    {
        return view('tetfund-bims-module::pages.bims_records.create');
    }

    public function store(Organization $org, CreateBIMSRecordRequest $request)
    {
        $input = $request->all();

        /** @var BIMSRecord $bIMSRecord */
        $bIMSRecord = BIMSRecord::create($input);

        BIMSRecordCreated::dispatch($bIMSRecord);
        return redirect(route('bims-onboarding.BIMSRecords.index'));
    }

    public function show(Organization $org, $id)
    {
        $current_user = Auth()->user();

        /** @var BIMSRecord $bIMSRecord */
        $bIMSRecord = BIMSRecord::find($id);

        if (empty($bIMSRecord)) {
            return redirect(route('bims-onboarding.BIMSRecords.index'));
        }

        return view('tetfund-bims-module::pages.bims_records.show')
                            ->with('bIMSRecord', $bIMSRecord)
                            ->with('current_user', $current_user)
                            ->with('months_list', BaseController::monthsList())
                            ->with('states_list', BaseController::statesList());
    }

    public function edit(Organization $org, $id)
    {
        $current_user = Auth()->user();

        /** @var BIMSRecord $bIMSRecord */
        $bIMSRecord = BIMSRecord::find($id);

        if (empty($bIMSRecord)) {
            return redirect(route('bims-onboarding.BIMSRecords.index'));
        }

        return view('tetfund-bims-module::pages.bims_records.edit')
                            ->with('bIMSRecord', $bIMSRecord)
                            ->with('current_user', $current_user)
                            ->with('months_list', BaseController::monthsList())
                            ->with('states_list', BaseController::statesList());
    }

    public function update(Organization $org, $id, UpdateBIMSRecordRequest $request)
    {
        /** @var BIMSRecord $bIMSRecord */
        $bIMSRecord = BIMSRecord::find($id);

        if (empty($bIMSRecord || $bIMSRecord->is_verified)) {
            return redirect(route('bims-onboarding.BIMSRecords.index'));
        }

        // $bIMSRecord->fill($request->all());
        $bIMSRecord->fill($request->only($bIMSRecord->updatable));
        if($bIMSRecord->user_type="student"){
            $bIMSRecord->staff_number_imported = null;
            $bIMSRecord->staff_number_verified = null;
        }
        else {
            $bIMSRecord->matric_number_imported = null;
            $bIMSRecord->matric_number_verified = null;
        }
        $bIMSRecord->save();

        
        
        BIMSRecordUpdated::dispatch($bIMSRecord);
        return redirect(route('bims-onboarding.BIMSRecords.index'));
    }

    public function destroy(Organization $org, $id)
    {
        /** @var BIMSRecord $bIMSRecord */
        $bIMSRecord = BIMSRecord::find($id);

        if (empty($bIMSRecord)) {
            return redirect(route('bims-onboarding.BIMSRecords.index'));
        }

        $bIMSRecord->delete();

        BIMSRecordDeleted::dispatch($bIMSRecord);
        return redirect(route('bims-onboarding.BIMSRecords.index'));
    }
        
    public function processBulkUpload(Organization $org, Request $request){

        $current_user = Auth()->user();
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();

        $attachedFileName = $beneficiary_member->beneficiary_id . '.' . time() . '.' . $request->file->getClientOriginalExtension();
        $request->file->move(public_path('uploads'), $attachedFileName);
        $path_to_file = public_path('uploads').'/'.$attachedFileName;

        $loop = 1;
        $created_records_counter = 0;
        $duplicated_records_counter = 0;
        $duplicated_records="";

        $errors = [];
         
        //Process each line
        if (($handle = fopen($path_to_file, "r")) !== FALSE) {
            while (false !== ($data = fgetcsv($handle, 1024))) {

                /**
                 * Remove character encoding
                 * Remove invalid characters
                 * Strip slashes 
                 * Replace character encoding escape sequences with regular space
                */
                for ($i=0; $i < 6; $i++) { 
                    if(array_key_exists($i, $data)){
                        $data[$i] = utf8_decode($data[$i]); 
                        $data[$i] = iconv('UTF-8', 'UTF-8//IGNORE', $data[$i]); 
                        $data[$i] = stripcslashes($data[$i]); 
                        $data[$i] = preg_replace('/\\\\x[a-fA-F0-9]{2}/', '', $data[$i]); 
                    }
                }

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
                    // (empty($email)==false && BIMSRecord::where('email_imported',$email)->where('beneficiary_id',optional($beneficiary_member)->beneficiary_id)->count()>0) || 
                    //  (empty($valid_telephone)==false && BIMSRecord::where('phone_imported',$valid_telephone)->where('beneficiary_id',optional($beneficiary_member)->beneficiary_id)->count()>0) || 
                    //  (empty($staff_code)==false && str_contains($request->user_type,"academic") && BIMSRecord::where('staff_number_imported',$staff_code)->where('beneficiary_id',optional($beneficiary_member)->beneficiary_id)->count()>0) || 
                    //  (empty($matric_code)==false && str_contains($request->user_type,"student") && BIMSRecord::where('matric_number_imported',$matric_code)->where('beneficiary_id',optional($beneficiary_member)->beneficiary_id)->count()>0) || 
                    //  (empty($email)==false && BIMSRecord::where('email_verified',$email)->where('beneficiary_id',optional($beneficiary_member)->beneficiary_id)->count()>0) || 
                    //  (empty($valid_telephone)==false && BIMSRecord::where('phone_verified',$valid_telephone)->where('beneficiary_id',optional($beneficiary_member)->beneficiary_id)->count()>0) || 
                    //  (empty($staff_code)==false && str_contains($request->user_type,"academic") && BIMSRecord::where('staff_number_verified',$staff_code)->where('beneficiary_id',optional($beneficiary_member)->beneficiary_id)->count()>0) || 
                    //  (empty($matric_code)==false && str_contains($request->user_type,"student") && BIMSRecord::where('matric_number_verified',$matric_code)->where('beneficiary_id',optional($beneficiary_member)->beneficiary_id)->count()>0)
                    )
                {

                    //duplicate record detected.
                    $duplicated_records_counter++;

                    //duplicated records not created
                    $duplicated_records .= $duplicated_records_counter. " {$first_name} {$middle_name} {$last_name} {$email} {$valid_telephone}\n";

                    Log::info("Duplicate Record = {$first_name} {$middle_name} {$last_name} {$email} {$valid_telephone} {$beneficiary_member->beneficiary_id}");

                } else {

                    try{
                        $bims_record =  BIMSRecord::create([
                            'organization_id'=>$current_user->organization_id,
                            'beneficiary_id'=>$beneficiary_member->beneficiary_id,
                            'first_name_imported'=> str_replace("\xC2\xA0", " ", $first_name),
                            'middle_name_imported'=> str_replace("\xC2\xA0", " ", $middle_name),
                            'last_name_imported'=>  str_replace("\xC2\xA0", " ", $last_name),
                            'phone_imported'=>$valid_telephone,
                            'email_imported'=>$email,
                            'staff_number_imported' => (str_contains($request->user_type,"academic") ? $staff_code : null),
                            'matric_number_imported' => (str_contains($request->user_type,"student") ? $matric_code : null),
                            'user_status' => 'new-import',
                            'user_type' => $request->user_type,
                        ]);
                        
                        BIMSRecordCreated::dispatch($bims_record);
                        $created_records_counter++;

                    } catch (\Throwable $th) {
                        //throw $th;
                        \Log::error($th);
                    }
                }

                //Save the record.
                $loop++;
            }
        } else {
            $errors[] = 'The uploaded file is empty';
        }
        
        if (count($errors) > 0) {
            return $this->sendError($this->array_flatten($errors), 'Errors processing file');
        }
        return $this->sendResponse(
            $duplicated_records, 
            "Bulk Onboarding completed successfully - {$created_records_counter} new records saved, {$duplicated_records_counter} duplicate records."
        );
    }

    /**
     * Display a listing of the BIMRecords report.
     *
     * @param \TETFund\ThesisDigitization\DataTables\BIMSRecordReportDataTable $beneficiaryRerportDataTable
     * @return Response
     */
    public function report(Organization $org, BIMSRecordReportDataTable $BIMSRecordReportDataTable)
    {   

        return $BIMSRecordReportDataTable->render('tetfund-bims-module::pages.bims_records.report');
    }  
    
    /**
     * Show the form for verifying the specified resource data.
     *
     * @param \TETFund\ThesisDigitization\DataTables\BIMSRecordReportDataTable $beneficiaryRerportDataTable
     * @return \Illuminate\Http\Response
     */
    public function verify(Organization $org, $id, Request $request)
    {

        /** @var BIMSRecord $bIMSRecord */
        $bIMSRecord = BIMSRecord::find($id);


        if (empty($bIMSRecord) ) {
            return redirect(url('/'));
        }

        if($bIMSRecord->is_verified)
        return redirect(route('bims-onboarding.BIMSRecords.verified', $bIMSRecord->id))->with('success', 'BIMS Record already verified');
         
        return view('tetfund-bims-module::pages.bims_records.verify')
                            ->with('bIMSRecord', $bIMSRecord);
    }

    /* confim the specified resource data.
    *
     * @param \TETFund\ThesisDigitization\DataTables\BIMSRecordReportDataTable $beneficiaryRerportDataTable
    * @return \Illuminate\Http\Response
    */
    public function confirm(Organization $org, $id, UpdateBIMSRecordRequest $request)
    {

       /** @var BIMSRecord $bIMSRecord */
       $bIMSRecord = BIMSRecord::find($id);

       if (empty($bIMSRecord) ) {
           return redirect(url('/'));
       }

       if($bIMSRecord->is_verified){
            return redirect(route('bims-onboarding.BIMSRecords.verified', $bIMSRecord->id))->with('success', 'BIMS Record verified successfully');
       }
        $bIMSRecord->fill($request->all());
        $bIMSRecord->save();
        
        if($bIMSRecord->user_type=="student"){
            $bIMSRecord->staff_number_imported = null;
            $bIMSRecord->staff_number_verified = null;
        }
        else {
            $bIMSRecord->matric_number_imported = null;
            $bIMSRecord->matric_number_verified = null;
        }
        
        $verified_data = $bIMSRecord->toArray();
        // set all verifying data to verified 
        foreach ($request->all() as $key => $data) {
            if(!array_key_exists($key, $verified_data))
            continue;   
            
            if(str_ends_with($key, '_imported')) {
                $prop = substr($key, 0, strlen($key) - strlen('_imported') );
                $prop_verified = $prop."_verified";
                if(array_key_exists($prop_verified, $verified_data)){
                    $verified_data[$prop_verified] = $data;
                }
            }
        }

        // commit the verified data
        $bIMSRecord->update($verified_data);

        // set is verifed if updatable is confirmed
        $bIMSRecord->is_verified = true;
        $bIMSRecord->user_status =  'verified-by-owner';

        $bIMSRecord->save();
        PushRecordToBIMS::dispatch($bIMSRecord);

        return redirect(route('bims-onboarding.BIMSRecords.verified', $bIMSRecord->id))->with('success', 'B I M S Record verified successfully');
    }

    /* confim the specified resource data.
    *
    * @param \TETFund\ThesisDigitization\DataTables\BIMSRecordReportDataTable $beneficiaryRerportDataTable
    * @return \Illuminate\Http\Response
    */
    public function verified(Organization $org, $id, Request $request)
    {

       /** @var BIMSRecord $bIMSRecord */
       $bIMSRecord = BIMSRecord::find($id);

       if (empty($bIMSRecord) ) {
           return redirect(url('/'));
       }
        return view('tetfund-bims-module::pages.bims_records.verified');  
    }

    public function displayBIMSRecordRemoving(Organization $org)
    {   $current_user = Auth()->user();
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();

        return view('tetfund-bims-module::pages.bims_records.remove')
        ->with('current_user', $current_user)
        ->with('organization', optional($current_user)->organization)
        ->with('beneficiary', optional($beneficiary_member)->beneficiary);

    }

    public function processBulkRemove(Organization $org, Request $request){

        $current_user = Auth()->user();
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();
        $bIMSRecordList = $request->bIMSRecordList;

        // clean up input
        $bIMSRecordList = stripslashes($bIMSRecordList);
        $bIMSRecordList = strip_tags($bIMSRecordList);
        $bIMSRecordList = str_replace(array("\n", "\r"), ' ', $bIMSRecordList);
        $bIMSRecordList = explode(' ', $bIMSRecordList);
        $bIMSRecordList = array_unique($bIMSRecordList);

        $bIMSRecordList = array_filter($bIMSRecordList, function($value){
            return $value !== "";
        });

        $seenBIMSRecords = "";
        $removed_records = 0;

        foreach ($bIMSRecordList as $key => $value) {
            if(str_contains(strtolower($seenBIMSRecords), strtolower($value)))
            continue;

            $bIMSRecord = BIMSRecord::where('user_status', 'bims-active')
            ->where('beneficiary_id',optional($beneficiary_member)->beneficiary_id)
            ->where(function (Builder $query) use($value){
                return $query->where('email_imported', $value)
                ->orWhere('email_verified', $value)
                ->orWhere('phone_imported', $value)
                ->orWhere('phone_verified', $value)
                ->orWhere('staff_number_imported', $value)
                ->orWhere('staff_number_verified', $value)
                ->orWhere('matric_number_imported', $value)
                ->orWhere('matric_number_verified', $value);
            }) ->first();

            if(is_null($bIMSRecord))
                continue;
                
            $removed_records++;

            $seenBIMSRecords .=$removed_records.") ";
            $seenBIMSRecords.= strlen($bIMSRecord->first_name_verfied) >0? $bIMSRecord->first_name_verfied: $bIMSRecord->first_name_imported;
            $seenBIMSRecords.=" ";
            $seenBIMSRecords.= strlen($bIMSRecord->middle_name_verfied) >0? $bIMSRecord->middle_name_verfied: $bIMSRecord->middle_name_imported;
            $seenBIMSRecords.=" ";
            $seenBIMSRecords.= strlen($bIMSRecord->last_name_verfied) >0? $bIMSRecord->last_name_verfied: $bIMSRecord->last_name_imported; 
            $seenBIMSRecords.=" ";
            $seenBIMSRecords.= strlen($bIMSRecord->phone_verfied) >0? $bIMSRecord->phone_verfied: $bIMSRecord->phone_imported;
            $seenBIMSRecords.=" ";
            $seenBIMSRecords.= strlen($bIMSRecord->email_verfied) >0? $bIMSRecord->email_verfied: $bIMSRecord->email_imported;
            $seenBIMSRecords.=" ";

            if($bIMSRecord->user_type=='student')
                $seenBIMSRecords.= strlen($bIMSRecord->matric_number_verfied) >0? $bIMSRecord->matric_number_verfied: $bIMSRecord->matric_number_imported;
            else
            $seenBIMSRecords.= strlen($bIMSRecord->staff_number_verfied) >0? $bIMSRecord->staff_number_verfied: $bIMSRecord->staff_number_imported;
            $seenBIMSRecords.=PHP_EOL."</br>";

            RemoveRecordFromBIMS::dispatch($bIMSRecord);

        }

        return $this->sendResponse(
            $seenBIMSRecords, 
            "Bulk remove completed successfully - {$removed_records} records removed."
        ); 
    }

}
