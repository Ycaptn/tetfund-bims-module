<?php

namespace TETFund\BIMSOnboarding\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use TETFund\BIMSOnboarding\Models\BIMSKnownUser;

use TETFund\BIMSOnboarding\Events\BIMSKnownUserDeleted;


use Hasob\FoundationCore\Traits\ApiResponder;
use Hasob\FoundationCore\Models\Organization;

use Hasob\FoundationCore\Controllers\BaseController as AppBaseController;
use TETFund\BIMSOnboarding\Jobs\PushRecordToBIMS;
use TETFund\BIMSOnboarding\Jobs\RemoveRecordFromBIMS;

/**
 * Class BIMSKnownUserController
 * @package TETFund\BIMSOnboarding\Controllers\API
 */

class BIMSKnownUserAPIController extends AppBaseController
{

    use ApiResponder;

   
    public function index(Request $request, Organization $organization)
    {
        $query = BIMSKnownUser::query();

        if ($request->get('skip')) {
            $query->skip($request->get('skip'));
        }
        if ($request->get('limit')) {
            $query->limit($request->get('limit'));
        }
        
        if ($organization != null){
            $query->where('organization_id', $organization->id);
        }

        $bimsKnownUsers = $this->showAll($query->get());

        return $this->sendResponse($bimsKnownUsers->toArray(), 'BIMS known user record retrieved successfully');
    }

   
    public function store(CreateBIMSKnownUserAPIRequest $request, Organization $organization)
    {
        // 
    }

   
    public function show($id, Organization $organization)
    {
        /** @var BIMSKnownUser $bimsKnownUser */
        $bimsKnownUser = BIMSKnownUser::find($id);

        if (empty($bimsKnownUser)) {
            return $this->sendError('BIMS Record not found');
        }

        return $this->sendResponse($bimsKnownUser->toArray(), 'BIMS known user record retrieved successfully');
    }

   
    public function update($id, UpdateBIMSKnownUserAPIRequest $request, Organization $organization)
    {
       // 
    }

    
    public function destroy($id, Organization $organization)
    {
        /** @var BIMSKnownUser $bimsKnownUser */
        $bimsKnownUser = BIMSKnownUser::find($id);

        if (empty($bimsKnownUser)) {
            return $this->sendError('BIMS known user record was not found');
        }

        $bimsKnownUser->delete();
        BIMSKnownUserDeleted::dispatch($bimsKnownUser);
        return $this->sendSuccess('BIMS known user record deleted successfully');
    }


    public function syncBimsUsers(Organization $org, Request $request) {
        $current_user = auth()->user();
        $bims_auth_token = session()->get('bims_token');

        $bims_users_response = null;
        if (!empty($bims_auth_token)) {
            $get_users_endpoint = config('bims.BIMS_GET_USERS_URI', 'https://bims.tetfund.gov.ng/api/users');
            $get_bims_users_request = \Illuminate\Support\Facades\Http::acceptJson()
                        ->withToken($bims_auth_token)
                        ->withHeaders([
                            'Content-Type' => 'application/json',
                        ])
                        ->get($get_users_endpoint, [
                            // 'page_no' => 1,
                            // 'per_page' => '30',
                        ]);

            if ($get_bims_users_request->successful() && $get_bims_users_request->ok()) {
                $decode_json_response = json_decode($get_bims_users_request->body());
                $bims_users_response_data = $decode_json_response->data;
                
                // save records to known_bims_users table
                if (count($bims_users_response_data) > 0) {
                    foreach ($bims_users_response_data as $bims_user_data) {
                        
                        $bims_known_user_obj = BIMSKnownUser::where('email', $bims_user_data->email)->first();
                        if (empty($bims_known_user_obj)) {
                            $bims_known_user_obj = new BIMSKnownUser();            
                            $bims_known_user_obj->organization_id = $org->id;
                        }
                        
                        $bims_known_user_obj->beneficiary_id = null;
                        $bims_known_user_obj->bims_db_row_id = $bims_user_data->id;
                        $bims_known_user_obj->bims_id = $bims_user_data->bims_id;
                        $bims_known_user_obj->first_name = $bims_user_data->first_name;
                        $bims_known_user_obj->middle_name = $bims_user_data->middle_name;
                        $bims_known_user_obj->last_name = $bims_user_data->last_name;
                        $bims_known_user_obj->type = $bims_user_data->type;
                        $bims_known_user_obj->email = $bims_user_data->email;
                        // $bims_known_user_obj->gender = $bims_user_data->gender;
                        // $bims_known_user_obj->dob = $bims_user_data->dob;
                        $bims_known_user_obj->photo = $bims_user_data->photo;
                        $bims_known_user_obj->institution_meta_data = json_encode($bims_user_data->institution);
                        $bims_known_user_obj->save();

                    }
                }

                return $this->sendResponse($bims_users_response_data, 'BIMS users record retrieve successfully from BIMS server.');
            }
            
            // return $get_bims_users_request->throw();
            return $this->sendError('Oops. An error was encountered while retrieving BIMS users.');
        }
    }

}
