<?php

namespace TETFund\BIMSOnboarding\Requests\API;

use TETFund\BIMSOnboarding\Models\BIMSRecord;
use TETFund\BIMSOnboarding\Requests\AppBaseFormRequest;


class UpdateBIMSRecordAPIRequest extends AppBaseFormRequest
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

    /**
    * @OA\Property(
    *     title="organization_id",
    *     description="organization_id",
    *     type="string"
    * )
    */
    public $organization_id;

    /**
    * @OA\Property(
    *     title="beneficiary_id",
    *     description="beneficiary_id",
    *     type="string"
    * )
    */
    public $beneficiary_id;

    /**
    * @OA\Property(
    *     title="first_name_verified",
    *     description="first_name_verified",
    *     type="string"
    * )
    */
    public $first_name_verified;

    /**
    * @OA\Property(
    *     title="middle_name_verified",
    *     description="middle_name_verified",
    *     type="string"
    * )
    */
    public $middle_name_verified;

    /**
    * @OA\Property(
    *     title="last_name_verified",
    *     description="last_name_verified",
    *     type="string"
    * )
    */
    public $last_name_verified;

    /**
    * @OA\Property(
    *     title="name_title_verified",
    *     description="name_title_verified",
    *     type="string"
    * )
    */
    public $name_title_verified;

    /**
    * @OA\Property(
    *     title="name_suffix_verified",
    *     description="name_suffix_verified",
    *     type="string"
    * )
    */
    public $name_suffix_verified;

    /**
    * @OA\Property(
    *     title="matric_number_verified",
    *     description="matric_number_verified",
    *     type="string"
    * )
    */
    public $matric_number_verified;

    /**
    * @OA\Property(
    *     title="staff_number_verified",
    *     description="staff_number_verified",
    *     type="string"
    * )
    */
    public $staff_number_verified;

    /**
    * @OA\Property(
    *     title="email_verified",
    *     description="email_verified",
    *     type="string"
    * )
    */
    public $email_verified;

    /**
    * @OA\Property(
    *     title="phone_verified",
    *     description="phone_verified",
    *     type="string"
    * )
    */
    public $phone_verified;

    /**
    * @OA\Property(
    *     title="phone_network_verified",
    *     description="phone_network_verified",
    *     type="string"
    * )
    */
    public $phone_network_verified;

    /**
    * @OA\Property(
    *     title="bvn_verified",
    *     description="bvn_verified",
    *     type="string"
    * )
    */
    public $bvn_verified;

    /**
    * @OA\Property(
    *     title="nin_verified",
    *     description="nin_verified",
    *     type="string"
    * )
    */
    public $nin_verified;

    /**
    * @OA\Property(
    *     title="dob_verified",
    *     description="dob_verified",
    *     type="string"
    * )
    */
    public $dob_verified;

    /**
    * @OA\Property(
    *     title="gender_verified",
    *     description="gender_verified",
    *     type="string"
    * )
    */
    public $gender_verified;

    /**
    * @OA\Property(
    *     title="first_name_imported",
    *     description="first_name_imported",
    *     type="string"
    * )
    */
    public $first_name_imported;

    /**
    * @OA\Property(
    *     title="middle_name_imported",
    *     description="middle_name_imported",
    *     type="string"
    * )
    */
    public $middle_name_imported;

    /**
    * @OA\Property(
    *     title="last_name_imported",
    *     description="last_name_imported",
    *     type="string"
    * )
    */
    public $last_name_imported;

    /**
    * @OA\Property(
    *     title="name_title_imported",
    *     description="name_title_imported",
    *     type="string"
    * )
    */
    public $name_title_imported;

    /**
    * @OA\Property(
    *     title="name_suffix_imported",
    *     description="name_suffix_imported",
    *     type="string"
    * )
    */
    public $name_suffix_imported;

    /**
    * @OA\Property(
    *     title="matric_number_imported",
    *     description="matric_number_imported",
    *     type="string"
    * )
    */
    public $matric_number_imported;

    /**
    * @OA\Property(
    *     title="staff_number_imported",
    *     description="staff_number_imported",
    *     type="string"
    * )
    */
    public $staff_number_imported;

    /**
    * @OA\Property(
    *     title="email_imported",
    *     description="email_imported",
    *     type="string"
    * )
    */
    public $email_imported;

    /**
    * @OA\Property(
    *     title="phone_imported",
    *     description="phone_imported",
    *     type="string"
    * )
    */
    public $phone_imported;

    /**
    * @OA\Property(
    *     title="phone_network_imported",
    *     description="phone_network_imported",
    *     type="string"
    * )
    */
    public $phone_network_imported;

    /**
    * @OA\Property(
    *     title="bvn_imported",
    *     description="bvn_imported",
    *     type="string"
    * )
    */
    public $bvn_imported;

    /**
    * @OA\Property(
    *     title="nin_imported",
    *     description="nin_imported",
    *     type="string"
    * )
    */
    public $nin_imported;

    /**
    * @OA\Property(
    *     title="dob_imported",
    *     description="dob_imported",
    *     type="string"
    * )
    */
    public $dob_imported;

    /**
    * @OA\Property(
    *     title="gender_imported",
    *     description="gender_imported",
    *     type="string"
    * )
    */
    public $gender_imported;

    /**
    * @OA\Property(
    *     title="user_status",
    *     description="user_status",
    *     type="string"
    * )
    */
    public $user_status;

    /**
    * @OA\Property(
    *     title="user_type",
    *     description="user_type",
    *     type="string"
    * )
    */
    public $user_type;

    /**
    * @OA\Property(
    *     title="display_ordinal",
    *     description="display_ordinal",
    *     type="integer"
    * )
    */
    public $display_ordinal;

    /**
    * @OA\Property(
    *     title="admin_entered_record_issues",
    *     description="admin_entered_record_issues",
    *     type="string"
    * )
    */
    public $admin_entered_record_issues;

    /**
    * @OA\Property(
    *     title="admin_entered_record_notes",
    *     description="admin_entered_record_notes",
    *     type="string"
    * )
    */
    public $admin_entered_record_notes;

    /**
    * @OA\Property(
    *     title="verification_meta_data",
    *     description="verification_meta_data",
    *     type="string"
    * )
    */
    public $verification_meta_data;


}
