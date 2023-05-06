<?php

namespace TETFund\BIMSOnboarding\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use TETFund\BIMSOnboarding\Models\BIMSRecord;

use TETFund\BIMSOnboarding\Events\BIMSRecordCreated;
use TETFund\BIMSOnboarding\Events\BIMSRecordUpdated;
use TETFund\BIMSOnboarding\Events\BIMSRecordDeleted;

use TETFund\BIMSOnboarding\Requests\API\CreateBIMSRecordAPIRequest;
use TETFund\BIMSOnboarding\Requests\API\UpdateBIMSRecordAPIRequest;

use Hasob\FoundationCore\Traits\ApiResponder;
use Hasob\FoundationCore\Models\Organization;

use Hasob\FoundationCore\Controllers\BaseController as AppBaseController;

/**
 * Class BIMSRecordController
 * @package TETFund\BIMSOnboarding\Controllers\API
 */

class BIMSRecordAPIController extends AppBaseController
{

    use ApiResponder;

    /**
     * Display a listing of the BIMSRecord.
     * GET|HEAD /bIMSRecords
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, Organization $organization)
    {
        $query = BIMSRecord::query();

        if ($request->get('skip')) {
            $query->skip($request->get('skip'));
        }
        if ($request->get('limit')) {
            $query->limit($request->get('limit'));
        }
        
        if ($organization != null){
            $query->where('organization_id', $organization->id);
        }

        $bIMSRecords = $this->showAll($query->get());

        return $this->sendResponse($bIMSRecords->toArray(), 'B I M S Records retrieved successfully');
    }

    /**
     * Store a newly created BIMSRecord in storage.
     * POST /bIMSRecords
     *
     * @param CreateBIMSRecordAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateBIMSRecordAPIRequest $request, Organization $organization)
    {
        $input = $request->all();

        /** @var BIMSRecord $bIMSRecord */
        $bIMSRecord = BIMSRecord::create($input);
        
        BIMSRecordCreated::dispatch($bIMSRecord);
        return $this->sendResponse($bIMSRecord->toArray(), 'B I M S Record saved successfully');
    }

    /**
     * Display the specified BIMSRecord.
     * GET|HEAD /bIMSRecords/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id, Organization $organization)
    {
        /** @var BIMSRecord $bIMSRecord */
        $bIMSRecord = BIMSRecord::find($id);

        if (empty($bIMSRecord)) {
            return $this->sendError('B I M S Record not found');
        }

        return $this->sendResponse($bIMSRecord->toArray(), 'B I M S Record retrieved successfully');
    }

    /**
     * Update the specified BIMSRecord in storage.
     * PUT/PATCH /bIMSRecords/{id}
     *
     * @param int $id
     * @param UpdateBIMSRecordAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBIMSRecordAPIRequest $request, Organization $organization)
    {
        /** @var BIMSRecord $bIMSRecord */
        $bIMSRecord = BIMSRecord::find($id);

        if (empty($bIMSRecord)) {
            return $this->sendError('B I M S Record not found');
        }

        $bIMSRecord->fill($request->all());
        $bIMSRecord->save();
        
        BIMSRecordUpdated::dispatch($bIMSRecord);
        return $this->sendResponse($bIMSRecord->toArray(), 'BIMSRecord updated successfully');
    }

    /**
     * Remove the specified BIMSRecord from storage.
     * DELETE /bIMSRecords/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id, Organization $organization)
    {
        /** @var BIMSRecord $bIMSRecord */
        $bIMSRecord = BIMSRecord::find($id);

        if (empty($bIMSRecord)) {
            return $this->sendError('B I M S Record not found');
        }

        $bIMSRecord->delete();
        BIMSRecordDeleted::dispatch($bIMSRecord);
        return $this->sendSuccess('B I M S Record deleted successfully');
    }
}
