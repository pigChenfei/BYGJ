<?php

namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\PlayerInviteRewardLogDataTable;
use App\Http\Requests\Carrier;
use App\Http\Requests\Carrier\CreatePlayerInviteRewardLogRequest;
use App\Http\Requests\Carrier\UpdatePlayerInviteRewardLogRequest;
use App\Repositories\Carrier\PlayerInviteRewardLogRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class PlayerInviteRewardLogController extends AppBaseController
{
    /** @var  PlayerInviteRewardLogRepository */
    private $playerInviteRewardLogRepository;

    public function __construct(PlayerInviteRewardLogRepository $playerInviteRewardLogRepo)
    {
        $this->playerInviteRewardLogRepository = $playerInviteRewardLogRepo;
    }

    /**
     * Display a listing of the PlayerInviteRewardLog.
     *
     * @param PlayerInviteRewardLogDataTable $playerInviteRewardLogDataTable
     * @return Response
     */
    public function index(PlayerInviteRewardLogDataTable $playerInviteRewardLogDataTable)
    {
        return $playerInviteRewardLogDataTable->render('player_invite_reward_logs.index');
    }

    /**
     * Show the form for creating a new PlayerInviteRewardLog.
     *
     * @return Response
     */
    public function create()
    {
        return view('player_invite_reward_logs.create');
    }

    /**
     * Store a newly created PlayerInviteRewardLog in storage.
     *
     * @param CreatePlayerInviteRewardLogRequest $request
     *
     * @return Response
     */
    public function store(CreatePlayerInviteRewardLogRequest $request)
    {
        $input = $request->all();

        $playerInviteRewardLog = $this->playerInviteRewardLogRepository->create($input);

        Flash::success('Player Invite Reward Log saved successfully.');

        return redirect(route('playerInviteRewardLogs.index'));
    }

    /**
     * Display the specified PlayerInviteRewardLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $playerInviteRewardLog = $this->playerInviteRewardLogRepository->findWithoutFail($id);

        if (empty($playerInviteRewardLog)) {
            Flash::error('Player Invite Reward Log not found');

            return redirect(route('playerInviteRewardLogs.index'));
        }

        return view('player_invite_reward_logs.show')->with('playerInviteRewardLog', $playerInviteRewardLog);
    }

    /**
     * Show the form for editing the specified PlayerInviteRewardLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $playerInviteRewardLog = $this->playerInviteRewardLogRepository->findWithoutFail($id);

        if (empty($playerInviteRewardLog)) {
            Flash::error('Player Invite Reward Log not found');

            return redirect(route('playerInviteRewardLogs.index'));
        }

        return view('player_invite_reward_logs.edit')->with('playerInviteRewardLog', $playerInviteRewardLog);
    }

    /**
     * Update the specified PlayerInviteRewardLog in storage.
     *
     * @param  int              $id
     * @param UpdatePlayerInviteRewardLogRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePlayerInviteRewardLogRequest $request)
    {
        $playerInviteRewardLog = $this->playerInviteRewardLogRepository->findWithoutFail($id);

        if (empty($playerInviteRewardLog)) {
            Flash::error('Player Invite Reward Log not found');

            return redirect(route('playerInviteRewardLogs.index'));
        }

        $playerInviteRewardLog = $this->playerInviteRewardLogRepository->update($request->all(), $id);

        Flash::success('Player Invite Reward Log updated successfully.');

        return redirect(route('playerInviteRewardLogs.index'));
    }

    /**
     * Remove the specified PlayerInviteRewardLog from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $playerInviteRewardLog = $this->playerInviteRewardLogRepository->findWithoutFail($id);

        if (empty($playerInviteRewardLog)) {
            Flash::error('Player Invite Reward Log not found');

            return redirect(route('playerInviteRewardLogs.index'));
        }

        $this->playerInviteRewardLogRepository->delete($id);

        Flash::success('Player Invite Reward Log deleted successfully.');

        return redirect(route('playerInviteRewardLogs.index'));
    }
}
