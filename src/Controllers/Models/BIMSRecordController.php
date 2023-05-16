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

use Flash;

use Illuminate\Http\Request;
use Illuminate\Http\Response;


class BIMSRecordController extends BaseController
{

    public function index(Organization $org, BIMSRecordDataTable $bIMSRecordDataTable)
    {
        $current_user = Auth()->user();
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();

        $cdv_bims_records = new \Hasob\FoundationCore\View\Components\CardDataView(BIMSRecord::class, "tetfund-bims-module::pages.bims_records.card_view_item");
        $cdv_bims_records->setDataQuery(['organization_id'=>$org->id])
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

        $attachedFileName = time() . '.' . $request->file->getClientOriginalExtension();
        $request->file->move(public_path('uploads'), $attachedFileName);
        $path_to_file = public_path('uploads').'/'.$attachedFileName;

        $loop = 1;
        $created_records = 0;
        $duplicated_records = 0;
        $errors = [];

        //Process each line
        if (($handle = fopen($path_to_file, "r")) !== FALSE) {
            while (false !== ($data = fgetcsv($handle, 1024))) {
                
                $first_name = trim($data[0]);
                $middle_name = trim($data[1]);
                $last_name = trim($data[2]);
                $email = strtolower(trim($data[3]));
                $phone = trim($data[4]);
                
                $staff_code = trim($data[5]);
                $matric_code = trim($data[5]);

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
                        $phone_part = str_replace(" ","",$phone_part);

                        if (strlen($phone_part)==11){
                            $valid_telephone=$phone_part;
                            break;
                        }
                    }
                }

                //Check if the record is being duplicated
                //if the matric number exists, or if the phone exists, and if the email address exists
                if (BIMSRecord::where('email_imported',$email)->where('beneficiary_id',optional($beneficiary_member)->beneficiary_id)->count()>0 || 
                    BIMSRecord::where('phone_imported',$valid_telephone)->where('beneficiary_id',optional($beneficiary_member)->beneficiary_id)->count()>0 || 
                    (str_contains($request->user_type,"academic") && BIMSRecord::where('staff_number_imported',$staff_code)->where('beneficiary_id',optional($beneficiary_member)->beneficiary_id)->count()>0) || 
                    (str_contains($request->user_type,"student") && BIMSRecord::where('matric_number_imported',$matric_code)->where('beneficiary_id',optional($beneficiary_member)->beneficiary_id)->count()>0) || 
                    BIMSRecord::where('email_verified',$email)->where('beneficiary_id',optional($beneficiary_member)->beneficiary_id)->count()>0 || 
                    BIMSRecord::where('phone_verified',$valid_telephone)->where('beneficiary_id',optional($beneficiary_member)->beneficiary_id)->count()>0 || 
                    (str_contains($request->user_type,"academic") && BIMSRecord::where('staff_number_verified',$staff_code)->where('beneficiary_id',optional($beneficiary_member)->beneficiary_id)->count()>0) || 
                    (str_contains($request->user_type,"student") && BIMSRecord::where('matric_number_verified',$matric_code)->where('beneficiary_id',optional($beneficiary_member)->beneficiary_id)->count()>0)){

                    //duplicate record detected.
                    //$errors []= "Duplicate record detected for {$first_name} {$middle_name} {$last_name} {$email} {$valid_telephone}";
                    $duplicated_records++;

                } else {

                  $bims_record =  BIMSRecord::create([
                        'organization_id'=>$current_user->organization_id,
                        'beneficiary_id'=>$beneficiary_member->beneficiary_id,
                        'first_name_imported'=>$first_name,
                        'middle_name_imported'=>$middle_name,
                        'last_name_imported'=>$last_name,
                        'phone_imported'=>$valid_telephone,
                        'email_imported'=>$email,
                        'staff_number_imported' => (str_contains($request->user_type,"academic") ? $staff_code : null),
                        'matric_number_imported' => (str_contains($request->user_type,"student") ? $matric_code : null),
                        'user_status' => 'new-import',
                        'user_type' => $request->user_type,
                    ]);
                    
                    BIMSRecordCreated::dispatch($bims_record);

                    $created_records++;
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
            $created_records, 
            "Bulk Onboarding completed successfully - {$created_records} new records saved, {$duplicated_records} duplicate records."
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
     * @param  \App\Models\Airline  $airline
     * @return \Illuminate\Http\Response
     */
    public function verify(Organization $org, $id, Request $request)
    {
        $current_user = Auth()->user();

        /** @var BIMSRecord $bIMSRecord */
        $bIMSRecord = BIMSRecord::find($id);


        if (empty($bIMSRecord) ) {
            return redirect(route('bims-onboarding.BIMSRecords.index'));
        }

        if($bIMSRecord->is_verified){
            return redirect(route('bims-onboarding.BIMSRecords.show', $bIMSRecord->id))->with('success', 'BIMS record verified and confirmed');
        }

        return view('tetfund-bims-module::pages.bims_records.verify')
                            ->with('bIMSRecord', $bIMSRecord)
                            ->with('current_user', $current_user);
    }

    /* Show the form for verifying the specified resource data.
    *
    * @param  \App\Models\Airline  $airline
    * @return \Illuminate\Http\Response
    */
    public function confirm(Organization $org, $id, Request $request)
    {
       $current_user = Auth()->user();

       /** @var BIMSRecord $bIMSRecord */
       $bIMSRecord = BIMSRecord::find($id);

       if (empty($bIMSRecord) ) {
           return redirect(route('bims-onboarding.BIMSRecords.index'));
       }

       if($bIMSRecord->is_verified){
           return redirect(route('bims-onboarding.BIMSRecords.show', $bIMSRecord->id))->with('success', 'BIMS record is already verified');
       }
       $request_only_updatables = $request->only($bIMSRecord->updatable);

        
        // $bIMSRecord->fill($request->all());
        $bIMSRecord->fill($request_only_updatables);
        $bIMSRecord->save();
            
        $verified_data = $bIMSRecord->toArray();

        // set all verifying data to verified 
        foreach ($request_only_updatables as $key => $request_only_updatable) {
            if(!array_key_exists($key, $verified_data))
            continue;   
            
            if(str_ends_with($key, '_imported')) {
                $prop = substr($key, 0, strlen($key) - strlen('_imported') );
                $prop_verified = $prop."_verified";
                if(array_key_exists($prop_verified, $verified_data)){
                    $verified_data[$prop_verified] = $request_only_updatable;
                }
            }
        }

        // commit the verified data
        $bIMSRecord->update($verified_data);

        // set is verifed if updatable is confirmed
        $bIMSRecord->is_verified = $bIMSRecord->is_confirmed;
        $bIMSRecord->user_status = $bIMSRecord->is_confirmed? 'verified-by-owner' : $bIMSRecord->user_status;

        $bIMSRecord->save();

        return redirect(route('bims-onboarding.BIMSRecords.show', $bIMSRecord->id))
        ->with('bIMSRecord', $bIMSRecord)
        ->with('current_user', $current_user)
        ->with('success', 'B I M S Record verified successfully');   
    }

    public function displayBIMSRecordRemoving(Organization $org)
    { $current_user = Auth()->user();
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();

        $cdv_bims_records = new \Hasob\FoundationCore\View\Components\CardDataView(BIMSRecord::class, "tetfund-bims-module::pages.bims_records.card_view_item");
        $cdv_bims_records->setDataQuery(['organization_id'=>$org->id])
            ->setDataQuery(['is_verified'=>true])
            ->setDataQuery(['user_status'=>'bims-active'])
            ->addDataGroup('All','deleted_at',null)
            ->addDataGroup('Academic Staff','user_type','academic')
            ->addDataGroup('Non Academic','user_type','non-academic')
            ->addDataGroup('Student','user_type','student')
            ->addDataGroup('Others','user_type',null)
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

        return view('tetfund-bims-module::pages.bims_records.remove')
        ->with('current_user', $current_user)
        ->with('beneficiary', optional($beneficiary_member)->beneficiary)
        ->with('months_list', BaseController::monthsList())
        ->with('states_list', BaseController::statesList())
        ->with('cdv_bims_records', $cdv_bims_records);

    }
   

}
