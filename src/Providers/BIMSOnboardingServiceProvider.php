<?php

namespace TETFund\BIMSOnboarding\Providers;

use Hash;
use Response;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

use Hasob\FoundationCore\Models\User;
use Hasob\FoundationCore\Models\Setting;
use Hasob\FoundationCore\Models\Department;
use Hasob\FoundationCore\Models\Organization;
use Hasob\FoundationCore\Managers\OrganizationManager;

use Illuminate\Support\ServiceProvider;

class BIMSOnboardingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //Current organization
        $org = \FoundationCore::current_organization();

        //Roles in this application with their roles.
        \FoundationCore::register_roles([
            'bims-onboarding-admin'      =>  []
        ]);

        $app_settings = [

            // 'allow_broker_register_investor'=>['group_name'=>'DMO','display_type'=>'boolean','display_name'=>'Allow Broker Register Investor','display_ordinal'=>1],
            // 'allow_investor_change_broker'=>['group_name'=>'DMO','display_type'=>'boolean','display_name'=>'Allow Investor Change Broker','display_ordinal'=>2],
            
        ];

        if (Schema::hasTable('fc_organizations') && Schema::hasTable('fc_settings')){

            if ($org != null && \FoundationCore::has_feature('bims-onboarding',$org)){

                foreach($app_settings as $key=>$setting){
                    \FoundationCore::register_setting(
                        $org, 
                        $key, 
                        $setting['group_name'],
                        $setting['display_type'],
                        $setting['display_name'], 
                        "bims-onboarding", 
                        $setting['display_ordinal']
                    );
                }

                $setting_list = Setting::whereIn('key',array_keys($app_settings))->get();

                $app_setting_values = $setting_list->mapWithKeys(function($item,$key){
                    return [$item->key => $item->value];
                });

                \View::share('bims_onboarding', $app_setting_values);
            }
        }

        if (isset($org) && $org != null && \FoundationCore::has_feature('bims-onboarding',$org)){

            //\FoundationCore::register_dashboard($org, "tetfund-fa-module", "tetfund-fa-dashboard", "tetfund-fa-module::dashboard.snippet", 1);
            //\FoundationCore::register_right_panel($org, "tetfund-fa-module", "tetfund-fa-side-panel", "tetfund-fa-module::dashboard.partials.side-panel", 1);

            //Register the workables available in this module
            //Register the operations for this module   
            //Register the workflows available via this module
        }

    }



}
