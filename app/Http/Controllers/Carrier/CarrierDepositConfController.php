<?php

namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\CarrierDepositConfDataTable;
use App\Http\Requests\Carrier;
use App\Http\Requests\Carrier\CreateCarrierDepositConfRequest;
use App\Http\Requests\Carrier\UpdateCarrierDepositConfRequest;
use App\Models\Conf\CarrierDepositConf;
use App\Repositories\Carrier\CarrierDepositConfRepository;
//use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CarrierDepositConfController extends AppBaseController
{
    /** @var  CarrierDepositConfRepository */
    private $carrierDepositConfRepository;

    public function __construct(CarrierDepositConfRepository $carrierDepositConfRepo)
    {
        $this->carrierDepositConfRepository = $carrierDepositConfRepo;
    }

    /**
     * Display a listing of the CarrierDepositConf.
     *
     * @param CarrierDepositConfDataTable $carrierDepositConfDataTable
     * @return Response
     */
    public function index(Request $request)
    {
        $this->carrierDepositConfRepository->pushCriteria(new RequestCriteria($request));
        $carrierDepositConf = $this->carrierDepositConfRepository->first();

        if(empty($carrierDepositConf)){
            $carrierDepositConf = new CarrierDepositConf();
            $carrierDepositConf->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
            $carrierDepositConf->save();
        }
        return view('Carrier.carrier_deposit_confs.index')
            ->with('carrierDepositConfs', $carrierDepositConf);
    }

    /**
     * Show the form for creating a new CarrierDepositConf.
     *
     * @return Response
     */
    public function create()
    {
        return $this->sendSuccessResponse([]);
    }

    /**
     * Store a newly created CarrierDepositConf in storage.
     *
     * @param CreateCarrierDepositConfRequest $request
     *
     * @return Response
     */
    public function store(CreateCarrierDepositConfRequest $request)
    {
        return $this->sendSuccessResponse([]);
    }

    /**
     * Display the specified CarrierDepositConf.
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
     * Show the form for editing the specified CarrierDepositConf.
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
     * Update the specified CarrierDepositConf in storage.
     *
     * @param  int              $id
     * @param UpdateCarrierDepositConfRequest $request
     *
     * @return Response
     */
    public function update($id=null, UpdateCarrierDepositConfRequest $request)
    {
        $input = $request->all();
        $conf = CarrierDepositConf::first();
        $carrierDepositConf = $this->carrierDepositConfRepository->findWithoutFail($conf->id);
        if (empty($carrierDepositConf)) {
            return $this->sendNotFoundResponse();
        }
        $update_type = $request->get('update_type');
        try{
            \DB::transaction(function () use ($input,$conf,$request,$update_type){
                $this->carrierDepositConfRepository->update($input, $conf->id);
            });
            return $this->sendSuccessResponse(route('carrierDashLoginConfs.index'));
        }catch (\Exception $e){
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified CarrierDepositConf from storage.
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
