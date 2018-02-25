<?php

namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\CarrierPasswordRecoverySiteConfDataTable;
use App\Http\Requests\Carrier;
use App\Http\Requests\Carrier\CreateCarrierPasswordRecoverySiteConfRequest;
use App\Http\Requests\Carrier\UpdateCarrierPasswordRecoverySiteConfRequest;
use App\Models\Conf\CarrierPasswordRecoverySiteConf;
use App\Repositories\Carrier\CarrierPasswordRecoverySiteConfRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CarrierPasswordRecoverySiteConfController extends AppBaseController
{
    /** @var  CarrierPasswordRecoverySiteConfRepository */
    private $carrierPasswordRecoverySiteConfRepository;

    public function __construct(CarrierPasswordRecoverySiteConfRepository $carrierPasswordRecoverySiteConfRepo)
    {
        $this->carrierPasswordRecoverySiteConfRepository = $carrierPasswordRecoverySiteConfRepo;
    }

    /**
     * Display a listing of the CarrierPasswordRecoverySiteConf.
     *
     * @param CarrierPasswordRecoverySiteConfDataTable $carrierPasswordRecoverySiteConfDataTable
     * @return Response
     */
    public function index(Request $request)
    {
        $this->carrierPasswordRecoverySiteConfRepository->pushCriteria(new RequestCriteria($request));
        $carrierPasswordRecoverySiteConf = $this->carrierPasswordRecoverySiteConfRepository->first();

        if(empty($carrierPasswordRecoverySiteConf)){
            $carrierPasswordRecoverySiteConf = new CarrierPasswordRecoverySiteConf();
            $carrierPasswordRecoverySiteConf->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
            $carrierPasswordRecoverySiteConf->save();
        }
        return view('Carrier.carrier_password_recovery_site_confs.index')
            ->with('carrierPasswordRecoverySiteConfs', $carrierPasswordRecoverySiteConf);
    }

    /**
     * Show the form for creating a new CarrierPasswordRecoverySiteConf.
     *
     * @return Response
     */
    public function create()
    {
        return $this->sendSuccessResponse([]);
    }

    /**
     * Store a newly created CarrierPasswordRecoverySiteConf in storage.
     *
     * @param CreateCarrierPasswordRecoverySiteConfRequest $request
     *
     * @return Response
     */
    public function store(CreateCarrierPasswordRecoverySiteConfRequest $request)
    {
        return $this->sendSuccessResponse([]);
    }

    /**
     * Display the specified CarrierPasswordRecoverySiteConf.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        return $this->sendSuccessResponse([]);
    }

    /**
     * Show the form for editing the specified CarrierPasswordRecoverySiteConf.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        return $this->sendSuccessResponse([]);
    }

    /**
     * Update the specified CarrierPasswordRecoverySiteConf in storage.
     *
     * @param  int              $id
     * @param UpdateCarrierPasswordRecoverySiteConfRequest $request
     *
     * @return Response
     */
    public function update($id = null, UpdateCarrierPasswordRecoverySiteConfRequest $request)
    {
        $input = $request->all();
        $conf = CarrierPasswordRecoverySiteConf::first();
        $carrierPasswordRecoveryConf = $this->carrierPasswordRecoverySiteConfRepository->findWithoutFail($conf->id);
        if (empty($carrierPasswordRecoveryConf)) {
            return $this->sendNotFoundResponse();
        }
        $update_type = $request->get('update_type');
        try{
            \DB::transaction(function () use ($input,$conf,$request,$update_type){
                $this->carrierPasswordRecoverySiteConfRepository->update($input, $conf->id);
            });
            return $this->sendSuccessResponse(route('carrierDashLoginConfs.index'));
        }catch (\Exception $e){
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified CarrierPasswordRecoverySiteConf from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        return $this->sendSuccessResponse([]);
    }
}
