<?php

namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\CarrierQuotaConsumptionLogDataTable;
use App\Http\Requests\Carrier;
use App\Http\Requests\Carrier\CreateCarrierQuotaConsumptionLogRequest;
use App\Http\Requests\Carrier\UpdateCarrierQuotaConsumptionLogRequest;
use App\Repositories\Carrier\CarrierQuotaConsumptionLogRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class CarrierQuotaConsumptionLogController extends AppBaseController
{
    /** @var  CarrierQuotaConsumptionLogRepository */
    private $carrierQuotaConsumptionLogRepository;

    public function __construct(CarrierQuotaConsumptionLogRepository $carrierQuotaConsumptionLogRepo)
    {
        $this->carrierQuotaConsumptionLogRepository = $carrierQuotaConsumptionLogRepo;
    }

    /**
     * Display a listing of the CarrierQuotaConsumptionLog.
     *
     * @param CarrierQuotaConsumptionLogDataTable $carrierQuotaConsumptionLogDataTable
     * @return Response
     */
    public function index(CarrierQuotaConsumptionLogDataTable $carrierQuotaConsumptionLogDataTable)
    {
        return $carrierQuotaConsumptionLogDataTable->render('Carrier.carrier_quota_consumption_logs.index');
    }

    /**
     * Show the form for creating a new CarrierQuotaConsumptionLog.
     *
     * @return Response
     */
    public function create()
    {
        return view('Carrier.carrier_quota_consumption_logs.create');
    }

    /**
     * Store a newly created CarrierQuotaConsumptionLog in storage.
     *
     * @param CreateCarrierQuotaConsumptionLogRequest $request
     *
     * @return Response
     */
    public function store(CreateCarrierQuotaConsumptionLogRequest $request)
    {
        $input = $request->all();

        $carrierQuotaConsumptionLog = $this->carrierQuotaConsumptionLogRepository->create($input);

        Flash::success('Carrier Quota Consumption Log saved successfully.');

        return redirect(route('carrierQuotaConsumptionLogs.index'));
    }

    /**
     * Display the specified CarrierQuotaConsumptionLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $carrierQuotaConsumptionLog = $this->carrierQuotaConsumptionLogRepository->findWithoutFail($id);

        if (empty($carrierQuotaConsumptionLog)) {
            Flash::error('Carrier Quota Consumption Log not found');

            return redirect(route('carrierQuotaConsumptionLogs.index'));
        }

        return view('Carrier.carrier_quota_consumption_logs.show')->with('carrierQuotaConsumptionLog', $carrierQuotaConsumptionLog);
    }

    /**
     * Show the form for editing the specified CarrierQuotaConsumptionLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $carrierQuotaConsumptionLog = $this->carrierQuotaConsumptionLogRepository->findWithoutFail($id);

        if (empty($carrierQuotaConsumptionLog)) {
            Flash::error('Carrier Quota Consumption Log not found');

            return redirect(route('carrierQuotaConsumptionLogs.index'));
        }

        return view('Carrier.carrier_quota_consumption_logs.edit')->with('carrierQuotaConsumptionLog', $carrierQuotaConsumptionLog);
    }

    /**
     * Update the specified CarrierQuotaConsumptionLog in storage.
     *
     * @param  int              $id
     * @param UpdateCarrierQuotaConsumptionLogRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCarrierQuotaConsumptionLogRequest $request)
    {
        $carrierQuotaConsumptionLog = $this->carrierQuotaConsumptionLogRepository->findWithoutFail($id);

        if (empty($carrierQuotaConsumptionLog)) {
            Flash::error('Carrier Quota Consumption Log not found');

            return redirect(route('carrierQuotaConsumptionLogs.index'));
        }

        $carrierQuotaConsumptionLog = $this->carrierQuotaConsumptionLogRepository->update($request->all(), $id);

        Flash::success('Carrier Quota Consumption Log updated successfully.');

        return redirect(route('carrierQuotaConsumptionLogs.index'));
    }

    /**
     * Remove the specified CarrierQuotaConsumptionLog from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $carrierQuotaConsumptionLog = $this->carrierQuotaConsumptionLogRepository->findWithoutFail($id);

        if (empty($carrierQuotaConsumptionLog)) {
            Flash::error('Carrier Quota Consumption Log not found');

            return redirect(route('carrierQuotaConsumptionLogs.index'));
        }

        $this->carrierQuotaConsumptionLogRepository->delete($id);

        Flash::success('Carrier Quota Consumption Log deleted successfully.');

        return redirect(route('carrierQuotaConsumptionLogs.index'));
    }
}
