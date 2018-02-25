<?php

namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\PlayerDepositPayLogDataTable;
use App\Http\Requests\Carrier;
use App\Http\Requests\Carrier\CreatePlayerDepositPayLogRequest;
use App\Http\Requests\Carrier\UpdatePlayerDepositPayLogRequest;
use App\Repositories\Carrier\PlayerDepositPayLogRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class PlayerDepositPayLogController extends AppBaseController
{
    /** @var  PlayerDepositPayLogRepository */
    private $playerDepositPayLogRepository;

    public function __construct(PlayerDepositPayLogRepository $playerDepositPayLogRepo)
    {
        $this->playerDepositPayLogRepository = $playerDepositPayLogRepo;
    }

    /**
     * Display a listing of the PlayerDepositPayLog.
     *
     * @param PlayerDepositPayLogDataTable $playerDepositPayLogDataTable
     * @return Response
     */
    public function index(PlayerDepositPayLogDataTable $playerDepositPayLogDataTable)
    {
        return $playerDepositPayLogDataTable->render('Carrier.player_deposit_pay_logs.index');
    }

    /**
     * Show the form for creating a new PlayerDepositPayLog.
     *
     * @return Response
     */
    public function create()
    {
        return view('Carrier.player_deposit_pay_logs.create');
    }

    /**
     * Store a newly created PlayerDepositPayLog in storage.
     *
     * @param CreatePlayerDepositPayLogRequest $request
     *
     * @return Response
     */
    public function store(CreatePlayerDepositPayLogRequest $request)
    {
        $input = $request->all();

        $playerDepositPayLog = $this->playerDepositPayLogRepository->create($input);

        Flash::success('Player Deposit Pay Log saved successfully.');

        return redirect(route('playerDepositPayLogs.index'));
    }

    /**
     * Display the specified PlayerDepositPayLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $playerDepositPayLog = $this->playerDepositPayLogRepository->findWithoutFail($id);

        if (empty($playerDepositPayLog)) {
            Flash::error('Player Deposit Pay Log not found');

            return redirect(route('playerDepositPayLogs.index'));
        }

        return view('Carrier.player_deposit_pay_logs.show')->with('playerDepositPayLog', $playerDepositPayLog);
    }

    /**
     * Show the form for editing the specified PlayerDepositPayLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $playerDepositPayLog = $this->playerDepositPayLogRepository->findWithoutFail($id);

        if (empty($playerDepositPayLog)) {
            Flash::error('Player Deposit Pay Log not found');

            return redirect(route('playerDepositPayLogs.index'));
        }

        return view('Carrier.player_deposit_pay_logs.edit')->with('playerDepositPayLog', $playerDepositPayLog);
    }

    /**
     * Update the specified PlayerDepositPayLog in storage.
     *
     * @param  int              $id
     * @param UpdatePlayerDepositPayLogRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePlayerDepositPayLogRequest $request)
    {
        $playerDepositPayLog = $this->playerDepositPayLogRepository->findWithoutFail($id);

        if (empty($playerDepositPayLog)) {
            Flash::error('Player Deposit Pay Log not found');

            return redirect(route('playerDepositPayLogs.index'));
        }

        $playerDepositPayLog = $this->playerDepositPayLogRepository->update($request->all(), $id);

        Flash::success('Player Deposit Pay Log updated successfully.');

        return redirect(route('playerDepositPayLogs.index'));
    }

    /**
     * Remove the specified PlayerDepositPayLog from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $playerDepositPayLog = $this->playerDepositPayLogRepository->findWithoutFail($id);

        if (empty($playerDepositPayLog)) {
            Flash::error('Player Deposit Pay Log not found');

            return redirect(route('playerDepositPayLogs.index'));
        }

        $this->playerDepositPayLogRepository->delete($id);

        Flash::success('Player Deposit Pay Log deleted successfully.');

        return redirect(route('playerDepositPayLogs.index'));
    }
}
