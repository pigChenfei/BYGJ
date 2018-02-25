<?php

namespace App\Http\Controllers\Carrier;

use App\Criteria\Carrier\CarrierGamePlatSelectCriteria;
use App\DataTables\Carrier\CarrierGamePlatDataTable;
use App\Http\Requests\Carrier;
use App\Http\Requests\Carrier\CreateCarrierGamePlatRequest;
use App\Http\Requests\Carrier\UpdateCarrierGamePlatRequest;
use App\Repositories\Carrier\CarrierGamePlatRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class CarrierGamePlatController extends AppBaseController
{
    /** @var  CarrierGamePlatRepository */
    private $carrierGamePlatRepository;

    public function __construct(CarrierGamePlatRepository $carrierGamePlatRepo)
    {
        $this->carrierGamePlatRepository = $carrierGamePlatRepo;
    }

    /**
     * Display a listing of the CarrierGamePlat.
     *
     * @param CarrierGamePlatDataTable $carrierGamePlatDataTable
     * @return Response
     */
    public function index(CarrierGamePlatDataTable $carrierGamePlatDataTable)
    {
        return $carrierGamePlatDataTable->render('Carrier.carrier_game_plats.index');
    }

    /**
     * Show the form for creating a new CarrierGamePlat.
     *
     * @return Response
     */
    public function create()
    {
        return view('Carrier.carrier_game_plats.create');
    }

    /**
     * Store a newly created CarrierGamePlat in storage.
     *
     * @param CreateCarrierGamePlatRequest $request
     *
     * @return Response
     */
    public function store(CreateCarrierGamePlatRequest $request)
    {
        $input = $request->all();
        $input['carrier_id'] = \Auth::user()->carrier_id;
        $carrierGamePlat = $this->carrierGamePlatRepository->create($input);
        if($request->ajax()){

            return self::sendResponse([],'ok');
        }

        Flash::success('Carrier Game Plat saved successfully.');

        return redirect(route('carrierGamePlats.index'));
    }

    /**
     * Display the specified CarrierGamePlat.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $carrierGamePlat = $this->carrierGamePlatRepository->findWithoutFail($id);

        if (empty($carrierGamePlat)) {
            Flash::error('Carrier Game Plat not found');

            return redirect(route('carrierGamePlats.index'));
        }

        return view('Carrier.carrier_game_plats.show')->with('carrierGamePlat', $carrierGamePlat);
    }

    /**
     * Show the form for editing the specified CarrierGamePlat.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $carrierGamePlat = $this->carrierGamePlatRepository->findWithoutFail($id);

        if (empty($carrierGamePlat)) {
            Flash::error('Carrier Game Plat not found');

            return redirect(route('carrierGamePlats.index'));
        }

        return view('Carrier.carrier_game_plats.edit')->with('carrierGamePlat', $carrierGamePlat);
    }

    /**
     * Update the specified CarrierGamePlat in storage.
     *
     * @param  int              $id
     * @param UpdateCarrierGamePlatRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCarrierGamePlatRequest $request)
    {
        $carrierGamePlat = $this->carrierGamePlatRepository->findWithoutFail($id);

        if (empty($carrierGamePlat)) {
            Flash::error('Carrier Game Plat not found');

            return redirect(route('carrierGamePlats.index'));
        }

        $carrierGamePlat = $this->carrierGamePlatRepository->update($request->all(), $id);

        if($request->ajax()){

            return self::sendResponse([],'ok');
        }

        Flash::success('游戏平台修改成功.');

        return redirect(route('carrierGamePlats.index'));
    }

    /**
     * Remove the specified CarrierGamePlat from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $carrierGamePlat = $this->carrierGamePlatRepository->findWithoutFail($id);

        if (empty($carrierGamePlat)) {
            Flash::error('Carrier Game Plat not found');

            return redirect(route('carrierGamePlats.index'));
        }

        $this->carrierGamePlatRepository->delete($id);

        Flash::success('Carrier Game Plat deleted successfully.');

        return redirect(route('carrierGamePlats.index'));
    }
}
