<?php

namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\CarrierActivityTypeDataTable;
use App\Criteria\Carrier\CarrierActivitySelectCriteria;
use App\Http\Requests\Carrier;
use App\Http\Requests\Carrier\CreateCarrierActivityTypeRequest;
use App\Http\Requests\Carrier\UpdateCarrierActivityTypeRequest;
use App\Models\CarrierActivity;
use App\Repositories\Carrier\CarrierActivityTypeRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Http\Request;

class CarrierActivityTypeController extends AppBaseController
{
    /** @var  CarrierActivityTypeRepository */
    private $carrierActivityTypeRepository;

    public function __construct(CarrierActivityTypeRepository $carrierActivityTypeRepo)
    {
        $this->carrierActivityTypeRepository = $carrierActivityTypeRepo;
    }

    /**
     * Display a listing of the CarrierActivityType.
     *
     * @param CarrierActivityTypeDataTable $carrierActivityTypeDataTable
     * @return Response
     */
    public function index(CarrierActivityTypeDataTable $carrierActivityTypeDataTable)
    {
        return $carrierActivityTypeDataTable->render('Carrier.carrier_activity_types.index');
    }

    /**
     * Show the form for creating a new CarrierActivityType.
     *
     * @return Response
     */
    public function create()
    {
        return view('Carrier.carrier_activity_types.create');
    }

    /**
     * Store a newly created CarrierActivityType in storage.
     *
     * @param CreateCarrierActivityTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateCarrierActivityTypeRequest $request)
    {
        $input = $request->all();
        $input['carrier_id'] = \Auth::user()->carrier_id;
        $this->carrierActivityTypeRepository->create($input);
        return $this->sendSuccessResponse( route('carrierActivityTypes.index'));
    }

    /**
     * Display the specified CarrierActivityType.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $carrierActivityType = $this->carrierActivityTypeRepository->findWithoutFail($id);
        if (empty($carrierActivityType)) {
            return redirect(route('carrierActivityTypes.index'));
        }
        return view('carrier_activity_types.show')->with('carrierActivityType', $carrierActivityType);
    }

    /**
     * Show the form for editing the specified CarrierActivityType.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $carrierActivityType = $this->carrierActivityTypeRepository->findWithoutFail($id);

        if (empty($carrierActivityType)) {
            return $this->sendNotFoundResponse();
        }

        return view('Carrier.carrier_activity_types.edit')->with('carrierActivityType', $carrierActivityType);
    }


    /**
     * @param $id
     * @param UpdateCarrierActivityTypeRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function update($id, UpdateCarrierActivityTypeRequest $request)
    {
        $carrierActivityType = $this->carrierActivityTypeRepository->findWithoutFail($id);
        if (empty($carrierActivityType)) {
            return $this->sendNotFoundResponse();
        }
        $this->carrierActivityTypeRepository->update($request->all(), $id);
        return $this->sendSuccessResponse(route('carrierActivityTypes.index'));
    }

    /**
     * Remove the specified CarrierActivityType from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id,Request $request)
    {
        $activity = CarrierActivity::where('act_type_id', $id)->first();

        if (!empty($activity)) {

            return $this->sendErrorResponse('该类型下有优惠活动，不能直接删除，可以关闭', 403);
        }

        $carrierActivityType = $this->carrierActivityTypeRepository->findWithoutFail($id);

        if (empty($carrierActivityType)) {

            return $this->sendNotFoundResponse();
        }

        $this->carrierActivityTypeRepository->delete($id);

        return $this->sendSuccessResponse(route('carrierActivityTypes.index'));

    }
    
    /**
     * 禁用启用
     * @param type $id
     * @param Request $request
     * @return type
     */
    public function saveStatus($id,Request $request)
    {
        $data['status'] = $request->get('status');
        $carrierAgentUser = $this->carrierActivityTypeRepository->update($data, $id);
        if (empty($carrierAgentUser)) {
            return $this->sendNotFoundResponse();
        }
        return $this->sendSuccessResponse( route('carrierAgentUsers.index'));
    }
}
