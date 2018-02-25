<?php

namespace App\Http\Controllers\Carrier;

use App\Http\Requests\Carrier\CreateCarrierGroupRequest;
use App\Http\Requests\Carrier\UpdateCarrierGroupRequest;
use App\Repositories\Carrier\CarrierGroupRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CarrierGroupController extends AppBaseController
{
    /** @var  CarrierGroupRepository */
    private $carrierGroupRepository;

    public function __construct(CarrierGroupRepository $carrierGroupRepo)
    {
        $this->carrierGroupRepository = $carrierGroupRepo;
    }

    /**
     * Display a listing of the CarrierGroup.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->carrierGroupRepository->pushCriteria(new RequestCriteria($request));
        $carrierGroups = $this->carrierGroupRepository->all();

        return view('carrier_groups.index')
            ->with('carrierGroups', $carrierGroups);
    }

    /**
     * Show the form for creating a new CarrierGroup.
     *
     * @return Response
     */
    public function create()
    {
        return view('carrier_groups.create');
    }

    /**
     * Store a newly created CarrierGroup in storage.
     *
     * @param CreateCarrierGroupRequest $request
     *
     * @return Response
     */
    public function store(CreateCarrierGroupRequest $request)
    {
        $input = $request->all();

        $carrierGroup = $this->carrierGroupRepository->create($input);

        Flash::success('Carrier Group saved successfully.');

        return redirect(route('carrierGroups.index'));
    }

    /**
     * Display the specified CarrierGroup.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $carrierGroup = $this->carrierGroupRepository->findWithoutFail($id);

        if (empty($carrierGroup)) {
            Flash::error('Carrier Group not found');

            return redirect(route('carrierGroups.index'));
        }

        return view('carrier_groups.show')->with('carrierGroup', $carrierGroup);
    }

    /**
     * Show the form for editing the specified CarrierGroup.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $carrierGroup = $this->carrierGroupRepository->findWithoutFail($id);

        if (empty($carrierGroup)) {
            Flash::error('Carrier Group not found');

            return redirect(route('carrierGroups.index'));
        }

        return view('carrier_groups.edit')->with('carrierGroup', $carrierGroup);
    }

    /**
     * Update the specified CarrierGroup in storage.
     *
     * @param  int              $id
     * @param UpdateCarrierGroupRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCarrierGroupRequest $request)
    {
        $carrierGroup = $this->carrierGroupRepository->findWithoutFail($id);

        if (empty($carrierGroup)) {
            Flash::error('Carrier Group not found');

            return redirect(route('carrierGroups.index'));
        }

        $carrierGroup = $this->carrierGroupRepository->update($request->all(), $id);

        Flash::success('Carrier Group updated successfully.');

        return redirect(route('carrierGroups.index'));
    }

    /**
     * Remove the specified CarrierGroup from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $carrierGroup = $this->carrierGroupRepository->findWithoutFail($id);

        if (empty($carrierGroup)) {
            Flash::error('Carrier Group not found');

            return redirect(route('carrierGroups.index'));
        }

        $this->carrierGroupRepository->delete($id);

        Flash::success('Carrier Group deleted successfully.');

        return redirect(route('carrierGroups.index'));
    }
}
