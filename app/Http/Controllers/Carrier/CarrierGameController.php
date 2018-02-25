<?php

namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\CarrierGameDataTable;
use App\Http\Requests\Carrier;
use App\Http\Requests\Carrier\CreateCarrierGameRequest;
use App\Http\Requests\Carrier\UpdateCarrierGameRequest;
use App\Repositories\Carrier\CarrierGamePlatRepository;
use App\Repositories\Carrier\CarrierGameRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class CarrierGameController extends AppBaseController
{
    /** @var  CarrierGameRepository */
    private $carrierGameRepository;

    public function __construct(CarrierGameRepository $carrierGameRepo)
    {
        $this->carrierGameRepository = $carrierGameRepo;
    }

    /**
     * Display a listing of the CarrierGame.
     *
     * @param CarrierGameDataTable $carrierGameDataTable
     * @return Response
     */
    public function index(CarrierGameDataTable $carrierGameDataTable,CarrierGamePlatRepository $carrierGamePlatRepo)
    {
//        $data['plat_id'] = $_POST['id'];
//        dd($data['plat_id']);
        $data = $carrierGamePlatRepo->with('gamePlat')->all();
        //dd($data);
        return $carrierGameDataTable->render('Carrier.carrier_games.index',['gamePlats'=>$data]);
    }

    public function getGames(){
        $id = $_POST['id'];
        dd($id);
    }
    /**
     * Show the form for creating a new CarrierGame.
     *
     * @return Response
     */
    public function create()
    {
        return view('Carrier.carrier_games.create');
    }

    /**
     * Store a newly created CarrierGame in storage.
     *
     * @param CreateCarrierGameRequest $request
     *
     * @return Response
     */
    public function store(CreateCarrierGameRequest $request)
    {
        $input = $request->all();
        $input['carrier_id'] = \Auth::user()->carrier_id;
        $carrierGame = $this->carrierGameRepository->create($input);
        if($request->ajax()){

            return self::sendResponse([],'ok');
        }

        Flash::success('Carrier Game saved successfully.');

        return redirect(route('carrierGames.index'));
    }

    /**
     * Display the specified CarrierGame.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $carrierGame = $this->carrierGameRepository->findWithoutFail($id);

        if (empty($carrierGame)) {
            Flash::error('Carrier Game not found');

            return redirect(route('carrierGames.index'));
        }

        return view('Carrier.carrier_games.show')->with('carrierGame', $carrierGame);
    }

    /**
     * Show the form for editing the specified CarrierGame.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $carrierGame = $this->carrierGameRepository->findWithoutFail($id);

        if (empty($carrierGame)) {
            Flash::error('Carrier Game not found');

            return redirect(route('carrierGames.index'));
        }

        return view('Carrier.carrier_games.edit')->with('carrierGame', $carrierGame);
    }

    /**
     * Update the specified CarrierGame in storage.
     *
     * @param  int              $id
     * @param UpdateCarrierGameRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCarrierGameRequest $request)
    {
        $carrierGame = $this->carrierGameRepository->findWithoutFail($id);

        if (empty($carrierGame)) {
            Flash::error('Carrier Game not found');

            return redirect(route('carrierGames.index'));
        }

        $carrierGame = $this->carrierGameRepository->update($request->all(), $id);

        if($request->ajax()){
            return self::sendResponse([],'ok');

        }
        Flash::success('游戏修改成功.');

        return redirect(route('carrierGames.index'));
    }

    /**
     * Remove the specified CarrierGame from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $carrierGame = $this->carrierGameRepository->findWithoutFail($id);

        if (empty($carrierGame)) {
            Flash::error('Carrier Game not found');

            return redirect(route('carrierGames.index'));
        }

        $this->carrierGameRepository->delete($id);

        Flash::success('Carrier Game deleted successfully.');

        return redirect(route('carrierGames.index'));
    }
}
