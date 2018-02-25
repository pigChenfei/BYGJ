<?php

namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\CarrierWithdrawConfDataTable;
use App\Http\Requests\Carrier;
use App\Http\Requests\Carrier\CreateCarrierWithdrawConfRequest;
use App\Http\Requests\Carrier\UpdateCarrierWithdrawConfRequest;
use App\Models\Conf\CarrierWithdrawConf;
use App\Repositories\Carrier\CarrierWithdrawConfRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CarrierWithdrawConfController extends AppBaseController
{
    /** @var  CarrierWithdrawConfRepository */
    private $carrierWithdrawConfRepository;

    public function __construct(CarrierWithdrawConfRepository $carrierWithdrawConfRepo)
    {
        $this->carrierWithdrawConfRepository = $carrierWithdrawConfRepo;
    }

    /**
     * Display a listing of the CarrierWithdrawConf.
     *
     * @param CarrierWithdrawConfDataTable $carrierWithdrawConfDataTable
     * @return Response
     */
    public function index(Request $request)
    {
        $this->carrierWithdrawConfRepository->pushCriteria(new RequestCriteria($request));
        $carrierWithdrawConf = $this->carrierWithdrawConfRepository->first();
        if(empty($carrierWithdrawConf)){
            $carrierWithdrawConf = new CarrierWithdrawConf();
            $carrierWithdrawConf->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
            $carrierWithdrawConf->save();
        }
        return view('Carrier.carrier_withdraw_confs.index')
            ->with('carrierWithdrawConfs', $carrierWithdrawConf);
    }

    /**
     * Show the form for creating a new CarrierWithdrawConf.
     *
     * @return Response
     */
    public function create()
    {
        return $this->sendSuccessResponse([]);
    }

    /**
     * Store a newly created CarrierWithdrawConf in storage.
     *
     * @param CreateCarrierWithdrawConfRequest $request
     *
     * @return Response
     */
    public function store(CreateCarrierWithdrawConfRequest $request)
    {
        return $this->sendSuccessResponse([]);
    }

    /**
     * Display the specified CarrierWithdrawConf.
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
     * Show the form for editing the specified CarrierWithdrawConf.
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
     * Update the specified CarrierWithdrawConf in storage.
     *
     * @param  int              $id
     * @param UpdateCarrierWithdrawConfRequest $request
     *
     * @return Response
     */
    public function update($id = null, UpdateCarrierWithdrawConfRequest $request)
    {
        $input = $request->all();
        $conf = CarrierWithdrawConf::first();
        $carrierWithdrawConf = $this->carrierWithdrawConfRepository->findWithoutFail($conf->id);
        if (empty($carrierWithdrawConf)) {
            return $this->sendNotFoundResponse();
        }
        $update_type = $request->get('update_type');
        try{
            \DB::transaction(function () use ($input,$conf,$request,$update_type){
                $this->carrierWithdrawConfRepository->update($input, $conf->id);
            });
            return $this->sendSuccessResponse(route('carrierDashLoginConfs.index'));
        }catch (\Exception $e){
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified CarrierWithdrawConf from storage.
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
