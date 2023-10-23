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
 * Class BIMSKnownUsers
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
class BIMSKnownUser extends Model
{
    use GuidId;
    // use OrganizationalConstraint;
    
    use SoftDeletes;

    use HasFactory;

    public $table = 'tf_bims_known_users';

    public $fillable = [
        'organization_idd',
        'beneficiary_id',
        'bims_db_row_id',
        'bims_id',
        'first_name',
        'middle_name',
        'last_name',
        'type',
        'email',
        'gender',
        'dob',
        'photo',
        'institution_meta_data',
    ];


    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'beneficiary_id' => 'string',
        'bims_db_row_id' => 'string',
        'bims_id' => 'string',
        'first_name' => 'string',
        'middle_name' => 'string',
        'last_name' => 'string',
        'type' => 'string',
        'email' => 'string',
        'gender' => 'string',
        'dob' => 'string',
        'photo' => 'string',
        'institution_meta_data' => 'string',
    ];

    protected $hidden = ['deleted_at'];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function beneficiary()
    {
        if (class_exists('App\Models\Beneficiary')) {
            return $this->belongsTo(\App\Models\Beneficiary::class, 'beneficiary_id');
        } elseif (class_exists('TETFund\AJLS\Models\Beneficiary')) {
            return $this->belongsTo(\TETFund\AJLS\Models\Beneficiary::class, 'beneficiary_id');
        }
    }

}
