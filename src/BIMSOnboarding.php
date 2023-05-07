<?php
namespace TETFund\BIMSOnboarding;

use Carbon;
use Session;
use Validator;

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

    public function get_menu_map(){

        $current_user = Auth::user();

        if ($current_user != null){

            $organization = $current_user->organization;
            if (\FoundationCore::has_feature('bims-onboarding', $organization)){        
                
                $fc_menu = [];

                    $fc_menu['mnu_bims_dashboard'] = [
                        'id'=>'mnu_bims_dashboard',
                        'label'=>'BIMS Onboarding',
                        'icon'=>'bx bx-user-plus',
                        'path'=> '',
                        'route-selector'=>'#',
                        'is-parent'=>true,
                        'children' => []
                    ];

                    $fc_menu['mnu_bims_dashboard']['children']["mnu_bims_dashboard_ids"] = [
                        'id'=>"mnu_bims_dashboard_ids",
                        'label'=>"Identities",
                        'icon'=>'bx bx-right-arrow-alt',
                        'path'=> route('bims-onboarding.bi-dashboard'),
                        'route-selector'=>'bims-onboarding/bi*',
                        'is-parent'=>false,
                        'children' => []
                    ];

                    $fc_menu['mnu_bims_dashboard']['children']["mnu_bims_dashboard_admin"] = [
                        'id'=>"mnu_bims_dashboard_admin",
                        'label'=>"BIMS Management",
                        'icon'=>'bx bx-right-arrow-alt',
                        'path'=> route('bims-onboarding.admin-dashboard'),
                        'route-selector'=>'bims-onboarding/admin*',
                        'is-parent'=>false,
                        'children' => []
                    ];

                if ($current_user->hasAnyRole(['admin','',''])){
                }

                return $fc_menu;
            }
        }
        return [];
    }

    public function api_routes(){
        Route::name('bims-onboarding-api.')->prefix('bims-onboarding-api')->group(function(){
            Route::resource('bims_records', \TETFund\BIMSOnboarding\Controllers\API\BIMSRecordAPIController::class);
        });
    }

    public function api_public_routes(){
    }

    public function public_routes(){
    }

    public function routes(){
        Route::name('bims-onboarding.')->prefix('bims-onboarding')->group(function(){
            Route::get('bi', [\TETFund\BIMSOnboarding\Controllers\Dashboard\DashboardController::class, 'displayBIDashboard'])->name('bi-dashboard');
            Route::get('admin', [\TETFund\BIMSOnboarding\Controllers\Dashboard\DashboardController::class, 'displayAdminDashboard'])->name('admin-dashboard');

            Route::get('bi-import', [\TETFund\BIMSOnboarding\Controllers\Models\BIMSRecordController::class, 'displayBIMSRecordOnboarding'])->name('bi-import');
            Route::post('bi-import', [\TETFund\BIMSOnboarding\Controllers\Models\BIMSRecordController::class, 'processBulkUpload'])->name('bi-import-processing');
            
            Route::resource('BIMSRecords', \TETFund\BIMSOnboarding\Controllers\Models\BIMSRecordController::class);
        });
    }


}