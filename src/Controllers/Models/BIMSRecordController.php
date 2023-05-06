<?php

namespace TETFund\BIMSOnboarding\Controllers\Models;

use TETFund\BIMSOnboarding\Models\BIMSRecord;

use TETFund\BIMSOnboarding\Events\BIMSRecordCreated;
use TETFund\BIMSOnboarding\Events\BIMSRecordUpdated;
use TETFund\BIMSOnboarding\Events\BIMSRecordDeleted;

use TETFund\BIMSOnboarding\Requests\CreateBIMSRecordRequest;
use TETFund\BIMSOnboarding\Requests\UpdateBIMSRecordRequest;

use TETFund\BIMSOnboarding\DataTables\BIMSRecordDataTable;

use Hasob\FoundationCore\Controllers\BaseController;
use Hasob\FoundationCore\Models\Organization;

use Flash;

use Illuminate\Http\Request;
use Illuminate\Http\Response;


class BIMSRecordController extends BaseController
{
    /**
     * Display a listing of the BIMSRecord.
     *
     * @param BIMSRecordDataTable $bIMSRecordDataTable
     * @return Response
     */
    public function index(Organization $org, BIMSRecordDataTable $bIMSRecordDataTable)
    {
        $current_user = Auth()->user();

        $cdv_b_i_m_s_records = new \Hasob\FoundationCore\View\Components\CardDataView(BIMSRecord::class, "tetfund-bims-module::pages.b_i_m_s_records.card_view_item");
        $cdv_b_i_m_s_records->setDataQuery(['organization_id'=>$org->id])
                        //->addDataGroup('label','field','value')
                        //->setSearchFields(['field_to_search1','field_to_search2'])
                        //->addDataOrder('display_ordinal','DESC')
                        //->addDataOrder('id','DESC')
                        ->enableSearch(true)
                        ->enablePagination(true)
                        ->setPaginationLimit(20)
                        ->setSearchPlaceholder('Search BIMSRecord');

        if (request()->expectsJson()){
            return $cdv_b_i_m_s_records->render();
        }

        return view('tetfund-bims-module::pages.b_i_m_s_records.card_view_index')
                    ->with('current_user', $current_user)
                    ->with('months_list', BaseController::monthsList())
                    ->with('states_list', BaseController::statesList())
                    ->with('cdv_b_i_m_s_records', $cdv_b_i_m_s_records);

        /*
        return $bIMSRecordDataTable->render('tetfund-bims-module::pages.b_i_m_s_records.index',[
            'current_user'=>$current_user,
            'months_list'=>BaseController::monthsList(),
            'states_list'=>BaseController::statesList()
        ]);
        */
    }

    /**
     * Show the form for creating a new BIMSRecord.
     *
     * @return Response
     */
    public function create(Organization $org)
    {
        return view('tetfund-bims-module::pages.b_i_m_s_records.create');
    }

    /**
     * Store a newly created BIMSRecord in storage.
     *
     * @param CreateBIMSRecordRequest $request
     *
     * @return Response
     */
    public function store(Organization $org, CreateBIMSRecordRequest $request)
    {
        $input = $request->all();

        /** @var BIMSRecord $bIMSRecord */
        $bIMSRecord = BIMSRecord::create($input);

        BIMSRecordCreated::dispatch($bIMSRecord);
        return redirect(route('bims-onboarding.bIMSRecords.index'));
    }

    /**
     * Display the specified BIMSRecord.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show(Organization $org, $id)
    {
        $current_user = Auth()->user();

        /** @var BIMSRecord $bIMSRecord */
        $bIMSRecord = BIMSRecord::find($id);

        if (empty($bIMSRecord)) {
            return redirect(route('bims-onboarding.bIMSRecords.index'));
        }

        return view('tetfund-bims-module::pages.b_i_m_s_records.show')
                            ->with('bIMSRecord', $bIMSRecord)
                            ->with('current_user', $current_user)
                            ->with('months_list', BaseController::monthsList())
                            ->with('states_list', BaseController::statesList());
    }

    /**
     * Show the form for editing the specified BIMSRecord.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit(Organization $org, $id)
    {
        $current_user = Auth()->user();

        /** @var BIMSRecord $bIMSRecord */
        $bIMSRecord = BIMSRecord::find($id);

        if (empty($bIMSRecord)) {
            return redirect(route('bims-onboarding.bIMSRecords.index'));
        }

        return view('tetfund-bims-module::pages.b_i_m_s_records.edit')
                            ->with('bIMSRecord', $bIMSRecord)
                            ->with('current_user', $current_user)
                            ->with('months_list', BaseController::monthsList())
                            ->with('states_list', BaseController::statesList());
    }

    /**
     * Update the specified BIMSRecord in storage.
     *
     * @param  int              $id
     * @param UpdateBIMSRecordRequest $request
     *
     * @return Response
     */
    public function update(Organization $org, $id, UpdateBIMSRecordRequest $request)
    {
        /** @var BIMSRecord $bIMSRecord */
        $bIMSRecord = BIMSRecord::find($id);

        if (empty($bIMSRecord)) {
            return redirect(route('bims-onboarding.bIMSRecords.index'));
        }

        $bIMSRecord->fill($request->all());
        $bIMSRecord->save();
        
        BIMSRecordUpdated::dispatch($bIMSRecord);
        return redirect(route('bims-onboarding.bIMSRecords.index'));
    }

    /**
     * Remove the specified BIMSRecord from storage.
     *
     * @param  int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Organization $org, $id)
    {
        /** @var BIMSRecord $bIMSRecord */
        $bIMSRecord = BIMSRecord::find($id);

        if (empty($bIMSRecord)) {
            return redirect(route('bims-onboarding.bIMSRecords.index'));
        }

        $bIMSRecord->delete();

        BIMSRecordDeleted::dispatch($bIMSRecord);
        return redirect(route('bims-onboarding.bIMSRecords.index'));
    }

        
    public function processBulkUpload(Organization $org, Request $request){

        $attachedFileName = time() . '.' . $request->file->getClientOriginalExtension();
        $request->file->move(public_path('uploads'), $attachedFileName);
        $path_to_file = public_path('uploads').'/'.$attachedFileName;

        //Process each line
        $loop = 1;
        $errors = [];
        $lines = file($path_to_file);

        if (count($lines) > 1) {
            foreach ($lines as $line) {
                
                if ($loop > 1) {
                    $data = explode(',', $line);
                    // if (count($invalids) > 0) {
                    //     array_push($errors, $invalids);
                    //     continue;
                    // }else{
                    //     //Check if line is valid
                    //     if (!$valid) {
                    //         $errors[] = $msg;
                    //     }
                    // }
                }
                $loop++;
            }
        }else{
            $errors[] = 'The uploaded csv file is empty';
        }
        
        if (count($errors) > 0) {
            return $this->sendError($this->array_flatten($errors), 'Errors processing file');
        }
        return $this->sendResponse($subject->toArray(), 'Bulk upload completed successfully');
    }
}
