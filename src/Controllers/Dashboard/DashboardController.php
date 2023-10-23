<?php

namespace TETFund\BIMSOnboarding\Controllers\Dashboard;

use TETFund\BIMSOnboarding\DataTables\BIMSRecordDataTable;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Hasob\FoundationCore\Controllers\BaseController;
use Hasob\FoundationCore\Models\Organization;


class DashboardController extends BaseController
{

    public function displayDashboard(Organization $org, Request $request){

        $current_user = Auth()->user();

        return view('tetfund-bims-module::dashboard.index')
                    ->with('organization', $org);
    }


    public function displayBIDashboard(Organization $org, Request $request){

        $current_user = Auth()->user();

        if (class_exists('App\Models\BeneficiaryMember')) {
            $beneficiaryMemberOBJ = app('App\Models\BeneficiaryMember');
        } elseif (class_exists('TETFund\AJLS\Models\BeneficiaryMember')) {
            $beneficiaryMemberOBJ = app('TETFund\AJLS\Models\BeneficiaryMember');
        }

        $beneficiary_member = $beneficiaryMemberOBJ->where('beneficiary_user_id', $current_user->id)->first();

        return view('tetfund-bims-module::dashboard.bi-dashboard')
                    ->with('organization', $org)
                    ->with('current_user', $current_user)
                    ->with('beneficiary', optional($beneficiary_member)->beneficiary);
    }

    public function displayAdminDashboard(Organization $org, Request $request, BIMSRecordDataTable $bIMSRecordDataTable){

        $current_user = Auth()->user();

        return view('tetfund-bims-module::dashboard.admin-dashboard')
                    ->with('organization', $org)
                    ->with('current_user', $current_user);



        /*
        return $bIMSRecordDataTable->render('tetfund-bims-module::pages.bims_records.index',[
            'current_user'=>$current_user,
            'months_list'=>BaseController::monthsList(),
            'states_list'=>BaseController::statesList()
        ]);
        */
    }

}
