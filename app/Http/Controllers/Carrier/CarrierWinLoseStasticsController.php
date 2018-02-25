<?php

namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\CarrierWinLoseStasticsDataTable;
use App\Http\Requests\Carrier;
use App\Http\Requests\Carrier\CreateCarrierWinLoseStasticsRequest;
use App\Http\Requests\Carrier\UpdateCarrierWinLoseStasticsRequest;
use App\Repositories\Carrier\CarrierWinLoseStasticsRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class CarrierWinLoseStasticsController extends AppBaseController
{
    /** @var  CarrierWinLoseStasticsRepository */
    private $carrierWinLoseStasticsRepository;

    public function __construct(CarrierWinLoseStasticsRepository $carrierWinLoseStasticsRepo)
    {
        $this->carrierWinLoseStasticsRepository = $carrierWinLoseStasticsRepo;
    }

    /**
     * Display a listing of the CarrierWinLoseStastics.
     *
     * @param CarrierWinLoseStasticsDataTable $carrierWinLoseStasticsDataTable
     * @return Response
     */
    public function index(CarrierWinLoseStasticsDataTable $carrierWinLoseStasticsDataTable)
    {
        return $carrierWinLoseStasticsDataTable->render('Carrier.carrier_win_lose_stastics.index');
    }

    /**
     * Show the form for creating a new CarrierWinLoseStastics.
     *
     * @return Response
     */
    public function create()
    {
        return view('Carrier.carrier_win_lose_stastics.create');
    }

    /**
     * Store a newly created CarrierWinLoseStastics in storage.
     *
     * @param CreateCarrierWinLoseStasticsRequest $request
     *
     * @return Response
     */
    public function store(CreateCarrierWinLoseStasticsRequest $request)
    {
        $input = $request->all();

        $carrierWinLoseStastics = $this->carrierWinLoseStasticsRepository->create($input);

        Flash::success('Carrier Win Lose Stastics saved successfully.');

        return redirect(route('carrierWinLoseStastics.index'));
    }

    /**
     * Display the specified CarrierWinLoseStastics.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $carrierWinLoseStastics = $this->carrierWinLoseStasticsRepository->findWithoutFail($id);

        if (empty($carrierWinLoseStastics)) {
            Flash::error('Carrier Win Lose Stastics not found');

            return redirect(route('carrierWinLoseStastics.index'));
        }

        return view('Carrier.carrier_win_lose_stastics.show')->with('carrierWinLoseStastics', $carrierWinLoseStastics);
    }

    /**
     * Show the form for editing the specified CarrierWinLoseStastics.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $carrierWinLoseStastics = $this->carrierWinLoseStasticsRepository->findWithoutFail($id);

        if (empty($carrierWinLoseStastics)) {
            Flash::error('Carrier Win Lose Stastics not found');

            return redirect(route('carrierWinLoseStastics.index'));
        }

        return view('Carrier.carrier_win_lose_stastics.edit')->with('carrierWinLoseStastics', $carrierWinLoseStastics);
    }

    /**
     * Update the specified CarrierWinLoseStastics in storage.
     *
     * @param  int              $id
     * @param UpdateCarrierWinLoseStasticsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCarrierWinLoseStasticsRequest $request)
    {
        $carrierWinLoseStastics = $this->carrierWinLoseStasticsRepository->findWithoutFail($id);

        if (empty($carrierWinLoseStastics)) {
            Flash::error('Carrier Win Lose Stastics not found');

            return redirect(route('carrierWinLoseStastics.index'));
        }

        $carrierWinLoseStastics = $this->carrierWinLoseStasticsRepository->update($request->all(), $id);

        Flash::success('Carrier Win Lose Stastics updated successfully.');

        return redirect(route('carrierWinLoseStastics.index'));
    }

    /**
     * Remove the specified CarrierWinLoseStastics from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $carrierWinLoseStastics = $this->carrierWinLoseStasticsRepository->findWithoutFail($id);

        if (empty($carrierWinLoseStastics)) {
            Flash::error('Carrier Win Lose Stastics not found');

            return redirect(route('carrierWinLoseStastics.index'));
        }

        $this->carrierWinLoseStasticsRepository->delete($id);

        Flash::success('Carrier Win Lose Stastics deleted successfully.');

        return redirect(route('carrierWinLoseStastics.index'));
    }
}
