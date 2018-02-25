<?php

namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\CarrierRegisterBasicConfDataTable;
use App\Http\Requests\Carrier;
use App\Http\Requests\Carrier\CreateCarrierRegisterBasicConfRequest;
use App\Http\Requests\Carrier\UpdateCarrierRegisterBasicConfRequest;
use App\Models\Conf\CarrierRegisterBasicConf;
use App\Repositories\Carrier\CarrierRegisterBasicConfRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CarrierRegisterBasicConfController extends AppBaseController
{
    /** @var  CarrierRegisterBasicConfRepository */
    private $carrierRegisterBasicConfRepository;

    public function __construct(CarrierRegisterBasicConfRepository $carrierRegisterBasicConfRepo)
    {
        $this->carrierRegisterBasicConfRepository = $carrierRegisterBasicConfRepo;
    }

    /**
     * Display a listing of the CarrierRegisterBasicConf.
     *
     * @param CarrierRegisterBasicConfDataTable $carrierRegisterBasicConfDataTable
     * @return Response
     */
    public function index(Request $request)
    {
        $this->carrierRegisterBasicConfRepository->pushCriteria(new RequestCriteria($request));
        $carrierRegisterBasicConf = $this->carrierRegisterBasicConfRepository->first();
        if(empty($carrierRegisterBasicConf)){
            $carrierRegisterBasicConf = new CarrierRegisterBasicConf();
            $carrierRegisterBasicConf->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
            $carrierRegisterBasicConf->save();
        }
        return view('Carrier.carrier_register_basic_confs.index')
            ->with('carrierRegisterBasicConfs', $carrierRegisterBasicConf);
    }

    /**
     * Show the form for creating a new CarrierRegisterBasicConf.
     *
     * @return Response
     */
    public function create()
    {
        return $this->sendSuccessResponse([]);
    }

    /**
     * Store a newly created CarrierRegisterBasicConf in storage.
     *
     * @param CreateCarrierRegisterBasicConfRequest $request
     *
     * @return Response
     */
    public function store(CreateCarrierRegisterBasicConfRequest $request)
    {
        return $this->sendSuccessResponse([]);
    }

    /**
     * Display the specified CarrierRegisterBasicConf.
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
     * Show the form for editing the specified CarrierRegisterBasicConf.
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
     * Update the specified CarrierRegisterBasicConf in storage.
     *
     * @param  int              $id
     * @param UpdateCarrierRegisterBasicConfRequest $request
     *
     * @return Response
     */
    public function update($id = null, UpdateCarrierRegisterBasicConfRequest $request)
    {
        $input = $request->all();
        foreach ($input as $key => $value){
            if(is_array($value)){
                $input[$key] = array_reduce($value,function($pre,$next){
                    return $pre + $next;
                },0);
            }
        }
        $conf = CarrierRegisterBasicConf::first();
        $carrierRegisterBasicConf = $this->carrierRegisterBasicConfRepository->findWithoutFail($conf->id);
        if (empty($carrierRegisterBasicConf)) {
            return $this->sendNotFoundResponse();
        }
        $update_type = $request->get('update_type');
        try{
            \DB::transaction(function () use ($input,$conf,$request,$update_type){
                $this->carrierRegisterBasicConfRepository->update($input, $conf->id);
            });
            return $this->sendSuccessResponse(route('carrierDashLoginConfs.index'));
        }catch (\Exception $e){
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified CarrierRegisterBasicConf from storage.
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
