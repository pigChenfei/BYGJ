<?php

namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\PlayerAccountLogDataTable;
use App\Http\Requests\Carrier;
use App\Http\Requests\Carrier\CreatePlayerAccountLogRequest;
use App\Http\Requests\Carrier\UpdatePlayerAccountLogRequest;
use App\Repositories\Carrier\PlayerAccountLogRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class PlayerAccountLogController extends AppBaseController
{
    /** @var  PlayerAccountLogRepository */
    private $playerAccountLogRepository;

    public function __construct(PlayerAccountLogRepository $playerAccountLogRepo)
    {
        $this->playerAccountLogRepository = $playerAccountLogRepo;
    }

    /**
     * Display a listing of the PlayerAccountLog.
     *
     * @param PlayerAccountLogDataTable $playerAccountLogDataTable
     * @return Response
     */
    public function index(PlayerAccountLogDataTable $playerAccountLogDataTable)
    {
        $gamePlats = \WinwinAuth::carrierUser()->carrier->mapGamePlats;
        return $playerAccountLogDataTable->render('Carrier.player_account_logs.index',['gamePlats' => $gamePlats]);
    }

    /**
     * Show the form for creating a new PlayerAccountLog.
     *
     * @return Response
     */
    public function create()
    {
        return view('Carrier.player_account_logs.create');
    }

    /**
     * Store a newly created PlayerAccountLog in storage.
     *
     * @param CreatePlayerAccountLogRequest $request
     *
     * @return Response
     */
    public function store(CreatePlayerAccountLogRequest $request)
    {
        $input = $request->all();

        $playerAccountLog = $this->playerAccountLogRepository->create($input);

        Flash::success('Player Account Log saved successfully.');

        return redirect(route('playerAccountLogs.index'));
    }

    /**
     * Display the specified PlayerAccountLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $playerAccountLog = $this->playerAccountLogRepository->findWithoutFail($id);

        if (empty($playerAccountLog)) {
            Flash::error('Player Account Log not found');

            return redirect(route('playerAccountLogs.index'));
        }

        return view('Carrier.player_account_logs.show')->with('playerAccountLog', $playerAccountLog);
    }

    /**
     * Show the form for editing the specified PlayerAccountLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $playerAccountLog = $this->playerAccountLogRepository->findWithoutFail($id);

        if (empty($playerAccountLog)) {
            Flash::error('Player Account Log not found');

            return redirect(route('playerAccountLogs.index'));
        }

        return view('Carrier.player_account_logs.edit')->with('playerAccountLog', $playerAccountLog);
    }

    /**
     * Update the specified PlayerAccountLog in storage.
     *
     * @param  int              $id
     * @param UpdatePlayerAccountLogRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePlayerAccountLogRequest $request)
    {
        $playerAccountLog = $this->playerAccountLogRepository->findWithoutFail($id);

        if (empty($playerAccountLog)) {
            Flash::error('Player Account Log not found');

            return redirect(route('playerAccountLogs.index'));
        }

        $playerAccountLog = $this->playerAccountLogRepository->update($request->all(), $id);

        Flash::success('Player Account Log updated successfully.');

        return redirect(route('playerAccountLogs.index'));
    }

    /**
     * Remove the specified PlayerAccountLog from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $playerAccountLog = $this->playerAccountLogRepository->findWithoutFail($id);

        if (empty($playerAccountLog)) {
            Flash::error('Player Account Log not found');

            return redirect(route('playerAccountLogs.index'));
        }

        $this->playerAccountLogRepository->delete($id);

        Flash::success('Player Account Log deleted successfully.');

        return redirect(route('playerAccountLogs.index'));
    }
}
