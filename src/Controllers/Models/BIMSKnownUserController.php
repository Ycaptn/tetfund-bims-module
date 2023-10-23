<?php

namespace TETFund\BIMSOnboarding\Controllers\Models;

use PDF;
use File;
use Flash;
use Session;
use Validator;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Builder;

use TETFund\BIMSOnboarding\Models\BIMSKnownUser;

use TETFund\BIMSOnboarding\Events\BIMSKnownUserCreated;
use TETFund\BIMSOnboarding\Events\BIMSKnownUserUpdated;
use TETFund\BIMSOnboarding\Events\BIMSKnownUserDeleted;

use TETFund\BIMSOnboarding\DataTables\BIMSKnownUserDataTable;

use Hasob\FoundationCore\Models\Organization;
use Hasob\FoundationCore\Controllers\BaseController;
use Hasob\FoundationCore\View\Components\CardDataView;

class BIMSKnownUserController extends BaseController
{

    public function index(Organization $org, BIMSKnownUserDataTable $bims_known_usersDataTable) {
        $current_user = Auth()->user();

        if (class_exists('App\Models\BeneficiaryMember')) {
            $beneficiaryMemberOBJ = app('App\Models\BeneficiaryMember');
        } elseif (class_exists('TETFund\AJLS\Models\BeneficiaryMember')) {
            $beneficiaryMemberOBJ = app('TETFund\AJLS\Models\BeneficiaryMember');
        }

        $beneficiary_member = $beneficiaryMemberOBJ->where('beneficiary_user_id', $current_user->id)->first();

        $cdv_bims_known_users = new CardDataView(BIMSKnownUser::class, 'tetfund-bims-module::pages.bims_known_users.card_view_item');

        $cdv_bims_known_users->setDataQuery(['organization_id'=>$org->id])
                // ->addDataGroup('All','deleted_at',null)
                ->setSearchFields(['bims_id','first_name', 'middle_name', 'last_name', 'email'])
                ->enableSearch(true)
                ->enablePagination(true)
                ->setPaginationLimit(30)
                ->setSearchPlaceholder('Search BIMS Known Users');

        if (request()->expectsJson()){
            return $cdv_bims_known_users->render();
        }

        return view('tetfund-bims-module::pages.bims_known_users.card_view_index')
                ->with('current_user', $current_user)
                ->with('cdv_bims_known_users', $cdv_bims_known_users);
    }


    public function create(Organization $org) {
        return view('tetfund-bims-module::pages.bims_known_users.create');
    }


    public function store(Organization $org, CreateBIMSKnownUserRequest $request) {
        $input = $request->all();

        /** @var BIMSKnownUser $bims_known_users */
        $bims_known_users = BIMSKnownUser::create($input);

        BIMSKnownUserCreated::dispatch($bims_known_users);
        return redirect(route('bims-onboarding.BIMSKnownUsers.index'));
    }


    public function show(Organization $org, $id) {
        $current_user = Auth()->user();

        /** @var BIMSKnownUser $bims_known_users */
        $bims_known_users = BIMSKnownUser::find($id);

        if (empty($bims_known_users)) {
            return redirect(route('bims-onboarding.BIMSKnownUsers.index'));
        }

        return view('tetfund-bims-module::pages.bims_known_users.show')
                ->with('bims_known_users', $bims_known_users)
                ->with('current_user', $current_user);
    }


    public function edit(Organization $org, $id) {
        $current_user = Auth()->user();

        /** @var BIMSKnownUser $bims_known_users */
        $bims_known_users = BIMSKnownUser::find($id);

        if (empty($bims_known_users)) {
            return redirect(route('bims-onboarding.BIMSKnownUsers.index'));
        }

        return view('tetfund-bims-module::pages.bims_known_users.edit')
                ->with('bims_known_users', $bims_known_users)
                ->with('current_user', $current_user);
    }


    public function update(Organization $org, $id, UpdateBIMSKnownUserRequest $request) {
        /** @var BIMSKnownUser $bims_known_users */
        $bims_known_users = BIMSKnownUser::find($id);

        if (empty($bims_known_users || $bims_known_users->is_verified)) {
            return redirect(route('bims-onboarding.BIMSKnownUsers.index'));
        }

        // $bims_known_users->fill($request->all());
        $bims_known_users->fill($request->only($bims_known_users->updatable));
        if($bims_known_users->user_type="student"){
            $bims_known_users->staff_number_imported = null;
            $bims_known_users->staff_number_verified = null;
        }
        else {
            $bims_known_users->matric_number_imported = null;
            $bims_known_users->matric_number_verified = null;
        }
        $bims_known_users->save();

        
        
        BIMSKnownUserUpdated::dispatch($bims_known_users);
        return redirect(route('bims-onboarding.BIMSKnownUsers.index'));
    }


    public function destroy(Organization $org, $id) {
        /** @var BIMSKnownUser $bims_known_users */
        $bims_known_users = BIMSKnownUser::find($id);

        if (empty($bims_known_users)) {
            return redirect(route('bims-onboarding.BIMSKnownUsers.index'));
        }

        $bims_known_users->delete();

        BIMSKnownUserDeleted::dispatch($bims_known_users);
        return redirect(route('bims-onboarding.BIMSKnownUsers.index'));
    }

}
