<?php

namespace TETFund\BIMSOnboarding\Requests;

use TETFund\BIMSOnboarding\Requests\AppBaseFormRequest;
use TETFund\BIMSOnboarding\Models\BIMSRecord;

class CreateBIMSRecordRequest extends AppBaseFormRequest
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
        return [
            'organization_id' => 'required|string|max:36|exists:fc_organizations,id',
            'beneficiary_id' => 'required|string|max:36|exists:tf_bi_portal_beneficiaries,id',
            'first_name_verified' => 'nullable|string|max:100',
            'middle_name_verified' => 'nullable|string|max:100',
            'last_name_verified' => 'nullable|string|max:100',
            'name_title_verified' => 'nullable|string|max:100',
            'name_suffix_verified' => 'nullable|string|max:100',
            'matric_number_verified' => 'nullable|string|max:100',
            'staff_number_verified' => 'nullable|string|max:100',
            'email_verified' => 'nullable|email|max:191',
            'phone_verified' => 'nullable|string|max:100',
            'phone_network_verified' => 'nullable|numeric|digits:11|starts_with:0',
            'bvn_verified' => 'nullable|string|max:100',
            'nin_verified' => 'nullable|string|max:100',
            'dob_verified' => 'nullable|string|max:100',
            'gender_verified' => 'nullable|string|max:100',
            'first_name_imported' => 'required|string|max:100',
            'middle_name_imported' => 'nullable|string|max:100',
            'last_name_imported' => 'required|string|max:100',
            'name_title_imported' => 'nullable|string|max:100',
            'name_suffix_imported' => 'nullable|string|max:100',
            'matric_number_imported' => 'nullable|string|max:100',
            'staff_number_imported' => 'nullable|string|max:100',
            'email_imported' => 'required|string|max:100',
            'phone_imported' => 'required|numeric|digits:11|starts_with:0',
            'phone_network_imported' =>'nullable|string|max:100',
            'bvn_imported' => 'nullable|digits:11',
            'nin_imported' => 'nullable|digits:11',
            'dob_imported' => 'nullable|string|max:100',
            'gender_imported' => 'required|string|max:100',
            'user_status' => 'nullable|string|max:100',
            'user_type' => 'required|string|max:100',
            'display_ordinal' => 'nullable|integer|min:0|max:365',
            'admin_entered_record_issues' => 'nullable|string|max:2000',
            'admin_entered_record_notes' => 'nullable|string|max:2000',
            'verification_meta_data' => 'nullable|string|max:2000'
        ];
    }
}
