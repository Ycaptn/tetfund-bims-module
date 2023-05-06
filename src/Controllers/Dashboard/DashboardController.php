<?php

namespace TETFund\BIMSOnboarding\Controllers\Dashboard;

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

}
