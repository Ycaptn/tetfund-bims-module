<?php
namespace TETFund\BIMSOnboarding;

use Carbon;
use Session;
use Validator;

use App\Models\Beneficiary;
use App\Models\BeneficiaryMember;

use TETFund\BIMSOnboarding\Models\BIMSRecord;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Hasob\FoundationCore\Models\User;


class BIMSOnboarding
{

    public function get_bims_records_count($user_type=null, $beneficiary_id=null, $verified=null){

        $current_user = Auth::user();
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();

        $query = BIMSRecord::where('deleted_at',null)->where('organization_id',$current_user->organization_id);

        if ($user_type!=null){
            $query = $query->where('user_type', $user_type);
        }
        if ($beneficiary_id!=null){
            $query = $query->where('beneficiary_id', $beneficiary_id);
        }else{
            if ($beneficiary_member!=null){
                $query = $query->where('beneficiary_id', $beneficiary_member->beneficiary_id);
            }
        }

        if ($verified!=null){
            $query = $query->where('is_verified', $verified);
        }

        return $query->count();
    }

    public function get_beneficiaries_on_bims(){
        return BIMSRecord::select('beneficiary_id')->distinct()->get();
    }

    public function get_menu_map(){

        $current_user = Auth::user();

        if ($current_user != null){

            $organization = $current_user->organization;
            if (\FoundationCore::has_feature('bims-onboarding', $organization)){        
                
                $fc_menu = [];

                if ($current_user->hasAnyRole(['admin','BI-desk-officer','BI-ict','BI-head-of-institution'])){

                    $fc_menu['mnu_bims_dashboard'] = [
                        'id'=>'mnu_bims_dashboard',
                        'label'=>'BIMS Onboarding',
                        'icon'=>'bx bx-group',
                        'path'=> '',
                        'route-selector'=>'#',
                        'is-parent'=>true,
                        'children' => []
                    ];

                    if ($current_user->hasAnyRole(['BI-desk-officer','BI-ict','BI-head-of-institution'])){
                        $fc_menu['mnu_bims_dashboard']['children']["mnu_bims_dashboard_ids"] = [
                            'id'=>"mnu_bims_dashboard_ids",
                            'label'=>"Identities",
                            'icon'=>'bx bx-right-arrow-alt',
                            'path'=> route('bims-onboarding.bi-dashboard'),
                            'route-selector'=>'bims-onboarding/bi*',
                            'is-parent'=>false,
                            'children' => []
                        ];
                    }

                    if ($current_user->hasAnyRole(['admin'])){
                        $fc_menu['mnu_bims_dashboard']['children']["mnu_bims_dashboard_admin"] = [
                            'id'=>"mnu_bims_dashboard_admin",
                            'label'=>"BIMS Management",
                            'icon'=>'bx bx-briefcase',
                            'path'=> route('bims-onboarding.admin-dashboard'),
                            'route-selector'=>'bims-onboarding/admin*',
                            'is-parent'=>false,
                            'children' => []
                        ];
                        $fc_menu['mnu_bims_dashboard']['children']["mnu_td_bim_record_report_admin"] = [
                            'id'=>'mnu_td_bim_record_report',
                            'label'=>'BIMS Record Report',
                            'icon'=>'bx bx-book-content',
                            'path'=> route('bims-onboarding.BIMSRecords.report'),
                            'route-selector'=>'bims-onboarding/BIMSRecords/report*',
                            'is-parent'=>false,
                            'children' => []
                        ];
                    }
                }

                return $fc_menu;
            }
        }
        return [];
    }

    public function api_routes(){
        Route::name('bims-onboarding-api.')->prefix('bims-onboarding-api')->group(function(){
            Route::put('bims_records/{bims_records}/remove_from_bims', [\TETFund\BIMSOnboarding\Controllers\API\BIMSRecordAPIController::class, 'removeFromBIMS'])->name('bims_records.remove_from_bims');
            Route::put('bims_records/{bims_records}/push_to_bims', [\TETFund\BIMSOnboarding\Controllers\API\BIMSRecordAPIController::class, 'pushToBIMS'])->name('bims_records.push_to_bims');
            Route::resource('bims_records', \TETFund\BIMSOnboarding\Controllers\API\BIMSRecordAPIController::class);
        });
    }

    public function api_public_routes(){
        Route::name('bims-onboarding-api.')->prefix('bims-onboarding-api')->group(function(){
            Route::put('bims_records/{bims_records}/confirm', [\TETFund\BIMSOnboarding\Controllers\API\BIMSRecordAPIController::class, 'confirm'])->name('bims_records.confirm');
        });
    }

    public function public_routes(){
        Route::name('bims-onboarding.')->prefix('bims-onboarding')->group(function(){
            Route::get('BIMSRecords/{BIMSRecords}/verify', [\TETFund\BIMSOnboarding\Controllers\Models\BIMSRecordController::class, 'verify'])->name('BIMSRecords.verify')->middleware('signed');
            Route::put('BIMSRecords/{BIMSRecords}/confirm', [\TETFund\BIMSOnboarding\Controllers\Models\BIMSRecordController::class, 'confirm'])->name('BIMSRecords.confirm');
            Route::get('BIMSRecords/{BIMSRecords}/verified', [\TETFund\BIMSOnboarding\Controllers\Models\BIMSRecordController::class, 'verified'])->name('BIMSRecords.verified');
        });
    }

    public function routes(){
        Route::name('bims-onboarding.')->prefix('bims-onboarding')->group(function(){
            Route::get('bi', [\TETFund\BIMSOnboarding\Controllers\Dashboard\DashboardController::class, 'displayBIDashboard'])->name('bi-dashboard');
            Route::get('admin', [\TETFund\BIMSOnboarding\Controllers\Dashboard\DashboardController::class, 'displayAdminDashboard'])->name('admin-dashboard');

            Route::get('bi-import', [\TETFund\BIMSOnboarding\Controllers\Models\BIMSRecordController::class, 'displayBIMSRecordOnboarding'])->name('bi-import');
            Route::post('bi-import', [\TETFund\BIMSOnboarding\Controllers\Models\BIMSRecordController::class, 'processBulkUpload'])->name('bi-import-processing');
            
            Route::get('bi-remove', [\TETFund\BIMSOnboarding\Controllers\Models\BIMSRecordController::class, 'displayBIMSRecordRemoving'])->name('bi-remove');

            Route::get('BIMSRecords/report', [\TETFund\BIMSOnboarding\Controllers\Models\BIMSRecordController::class, 'report'])->name('BIMSRecords.report');
            Route::resource('BIMSRecords', \TETFund\BIMSOnboarding\Controllers\Models\BIMSRecordController::class);

        });
    }

}