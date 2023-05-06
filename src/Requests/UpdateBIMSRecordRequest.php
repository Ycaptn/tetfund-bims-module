<?php

namespace TETFund\BIMSOnboarding\Requests;

use TETFund\BIMSOnboarding\Requests\AppBaseFormRequest;
use TETFund\BIMSOnboarding\Models\BIMSRecord;

class UpdateBIMSRecordRequest extends AppBaseFormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /*
        
        */
        return [
            'organization_id' => 'required',
        'beneficiary_id' => 'required',
        'first_name_verified' => 'nullable|max:300',
        'middle_name_verified' => 'nullable|max:300',
        'last_name_verified' => 'nullable|max:300',
        'name_title_verified' => 'nullable|max:300',
        'name_suffix_verified' => 'nullable|max:300',
        'matric_number_verified' => 'nullable|max:300',
        'staff_number_verified' => 'nullable|max:300',
        'email_verified' => 'nullable|max:300',
        'phone_verified' => 'nullable|max:300',
        'phone_network_verified' => 'nullable|max:300',
        'bvn_verified' => 'nullable|max:300',
        'nin_verified' => 'nullable|max:300',
        'dob_verified' => 'nullable|max:300',
        'gender_verified' => 'nullable|max:300',
        'first_name_imported' => 'nullable|max:300',
        'middle_name_imported' => 'nullable|max:300',
        'last_name_imported' => 'nullable|max:300',
        'name_title_imported' => 'nullable|max:300',
        'name_suffix_imported' => 'nullable|max:300',
        'matric_number_imported' => 'nullable|max:300',
        'staff_number_imported' => 'nullable|max:300',
        'email_imported' => 'nullable|max:300',
        'phone_imported' => 'nullable|max:300',
        'phone_network_imported' => 'nullable|max:300',
        'bvn_imported' => 'nullable|max:300',
        'nin_imported' => 'nullable|max:300',
        'dob_imported' => 'nullable|max:300',
        'gender_imported' => 'nullable|max:300',
        'user_status' => 'nullable|max:100',
        'user_type' => 'nullable|max:100',
        'display_ordinal' => 'nullable|min:0|max:365',
        'admin_entered_record_issues' => 'max:2000',
        'admin_entered_record_notes' => 'max:2000',
        'verification_meta_data' => 'max:2000'
        ];
    }
}
