<?php

namespace TETFund\BIMSOnboarding\Models;

use Hash;
use Response;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

use Hasob\Workflow\Traits\Workable;
use Hasob\FoundationCore\Traits\GuidId;
use Hasob\FoundationCore\Traits\Pageable;
use Hasob\FoundationCore\Traits\Disable;
use Hasob\FoundationCore\Traits\Ratable;
use Hasob\FoundationCore\Traits\Taggable;
use Hasob\FoundationCore\Traits\Ledgerable;
use Hasob\FoundationCore\Traits\Attachable;
use Hasob\FoundationCore\Traits\Artifactable;
use Hasob\FoundationCore\Traits\OrganizationalConstraint;

use Eloquent as Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class BIMSRecord
 * @package TETFund\BIMSOnboarding\Models
 * @version May 6, 2023, 10:34 pm UTC
 *
 * @property \TETFund\BIMSOnboarding\Models\Beneficiary $beneficiary
 * @property string $organization_id
 * @property string $beneficiary_id
 * @property string $first_name_verified
 * @property string $middle_name_verified
 * @property string $last_name_verified
 * @property string $name_title_verified
 * @property string $name_suffix_verified
 * @property string $matric_number_verified
 * @property string $staff_number_verified
 * @property string $email_verified
 * @property string $phone_verified
 * @property string $phone_network_verified
 * @property string $bvn_verified
 * @property string $nin_verified
 * @property string $dob_verified
 * @property string $gender_verified
 * @property string $first_name_imported
 * @property string $middle_name_imported
 * @property string $last_name_imported
 * @property string $name_title_imported
 * @property string $name_suffix_imported
 * @property string $matric_number_imported
 * @property string $staff_number_imported
 * @property string $email_imported
 * @property string $phone_imported
 * @property string $phone_network_imported
 * @property string $bvn_imported
 * @property string $nin_imported
 * @property string $dob_imported
 * @property string $gender_imported
 * @property string $user_status
 * @property string $user_type
 * @property string $admin_entered_record_issues
 * @property string $admin_entered_record_notes
 */
class BIMSRecord extends Model
{
    use GuidId;
    use OrganizationalConstraint;
    
    use SoftDeletes;

    use HasFactory;

    public $table = 'tf_bims_record';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'organization_id',
        'beneficiary_id',
        'first_name_verified',
        'middle_name_verified',
        'last_name_verified',
        'name_title_verified',
        'name_suffix_verified',
        'matric_number_verified',
        'staff_number_verified',
        'email_verified',
        'phone_verified',
        'phone_network_verified',
        'bvn_verified',
        'nin_verified',
        'dob_verified',
        'gender_verified',
        'first_name_imported',
        'middle_name_imported',
        'last_name_imported',
        'name_title_imported',
        'name_suffix_imported',
        'matric_number_imported',
        'staff_number_imported',
        'email_imported',
        'phone_imported',
        'phone_network_imported',
        'bvn_imported',
        'nin_imported',
        'dob_imported',
        'gender_imported',
        'user_status',
        'user_type',
        'admin_entered_record_issues',
        'admin_entered_record_notes',
    ];

    
    /**
     *  The attributes can be editable when not verified 
     *  
     *  @var array 
     */
    public $editable = [
        'matric_number_imported',
        'staff_number_imported',
        'email_imported',
        'phone_imported',
        'phone_network_imported',
        'user_type',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'first_name_verified' => 'string',
        'middle_name_verified' => 'string',
        'last_name_verified' => 'string',
        'name_title_verified' => 'string',
        'name_suffix_verified' => 'string',
        'matric_number_verified' => 'string',
        'staff_number_verified' => 'string',
        'email_verified' => 'string',
        'phone_verified' => 'string',
        'phone_network_verified' => 'string',
        'bvn_verified' => 'string',
        'nin_verified' => 'string',
        'dob_verified' => 'string',
        'gender_verified' => 'string',
        'first_name_imported' => 'string',
        'middle_name_imported' => 'string',
        'last_name_imported' => 'string',
        'name_title_imported' => 'string',
        'name_suffix_imported' => 'string',
        'matric_number_imported' => 'string',
        'staff_number_imported' => 'string',
        'email_imported' => 'string',
        'phone_imported' => 'string',
        'phone_network_imported' => 'string',
        'bvn_imported' => 'string',
        'nin_imported' => 'string',
        'dob_imported' => 'string',
        'gender_imported' => 'string',
        'user_status' => 'string',
        'user_type' => 'string',
        'display_ordinal' => 'integer',
        'admin_entered_record_issues' => 'string',
        'admin_entered_record_notes' => 'string',
        'verification_meta_data' => 'string',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function beneficiary()
    {
        return $this->belongsTo(\App\Models\Beneficiary::class, 'beneficiary_id');
    }

    public function updatableInputs(){
        $updatable_inputs = [];
        if($this->is_verified)
        return $updatable_inputs;

        $data = $this->toArray();
        foreach ($data as $key => $value) {
            if(str_ends_with($key, '_imported')) {
                $prop = substr($key, 0, strlen($key) - strlen('_imported') );
                $prop_verified = $prop."_verified";

                if(!array_key_exists($prop_verified, $data))
                continue;

                if( ($data[$key] != $data[$prop_verified]
                    || $data[$prop_verified] = "" 
                    || $data[$prop_verified] = null
                    )
                    && in_array($key, $this->editable)
                )
                $updatable_inputs = array_merge($updatable_inputs, [$key]);

            }else{
                if(in_array($key, $this->editable))
                $updatable_inputs = array_merge($updatable_inputs, [$key]);
            }
        }
        return $updatable_inputs;
    }

}
