<?php

namespace App\Http\Controllers\Carrier;

use App\Http\Requests\Carrier\CreatePlayerWithdrawFlowLimitLogDetailRequest;
use App\Http\Requests\Carrier\UpdatePlayerWithdrawFlowLimitLogDetailRequest;
use App\Repositories\Carrier\PlayerWithdrawFlowLimitLogDetailRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class PlayerWithdrawFlowLimitLogDetailController extends AppBaseController
{
    /** @var  PlayerWithdrawFlowLimitLogDetailRepository */
    private $playerWithdrawFlowLimitLogDetailRepository;

    public function __construct(PlayerWithdrawFlowLimitLogDetailRepository $playerWithdrawFlowLimitLogDetailRepo)
    {
        $this->playerWithdrawFlowLimitLogDetailRepository = $playerWithdrawFlowLimitLogDetailRepo;
    }

    /**
     * Display a listing of the PlayerWithdrawFlowLimitLogDetail.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->playerWithdrawFlowLimitLogDetailRepository->pushCriteria(new RequestCriteria($request));
        $playerWithdrawFlowLimitLogDetails = $this->playerWithdrawFlowLimitLogDetailRepository->all();

        return view('player_withdraw_flow_limit_log_details.index')
            ->with('playerWithdrawFlowLimitLogDetails', $playerWithdrawFlowLimitLogDetails);
    }

    /**
     * Show the form for creating a new PlayerWithdrawFlowLimitLogDetail.
     *
     * @return Response
     */
    public function create()
    {
        return view('player_withdraw_flow_limit_log_details.create');
    }

    /**
     * Store a newly created PlayerWithdrawFlowLimitLogDetail in storage.
     *
     * @param CreatePlayerWithdrawFlowLimitLogDetailRequest $request
     *
     * @return Response
     */
    public function store(CreatePlayerWithdrawFlowLimitLogDetailRequest $request)
    {
        $input = $request->all();

        $playerWithdrawFlowLimitLogDetail = $this->playerWithdrawFlowLimitLogDetailRepository->create($input);

        Flash::success('Player Withdraw Flow Limit Log Detail saved successfully.');

        return redirect(route('playerWithdrawFlowLimitLogDetails.index'));
    }

    /**
     * Display the specified PlayerWithdrawFlowLimitLogDetail.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $playerWithdrawFlowLimitLogDetail = $this->playerWithdrawFlowLimitLogDetailRepository->findWithoutFail($id);

        if (empty($playerWithdrawFlowLimitLogDetail)) {
            Flash::error('Player Withdraw Flow Limit Log Detail not found');

            return redirect(route('playerWithdrawFlowLimitLogDetails.index'));
        }

        return view('player_withdraw_flow_limit_log_details.show')->with('playerWithdrawFlowLimitLogDetail', $playerWithdrawFlowLimitLogDetail);
    }

    /**
     * Show the form for editing the specified PlayerWithdrawFlowLimitLogDetail.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $playerWithdrawFlowLimitLogDetail = $this->playerWithdrawFlowLimitLogDetailRepository->findWithoutFail($id);

        if (empty($playerWithdrawFlowLimitLogDetail)) {
            Flash::error('Player Withdraw Flow Limit Log Detail not found');

            return redirect(route('playerWithdrawFlowLimitLogDetails.index'));
        }

        return view('player_withdraw_flow_limit_log_details.edit')->with('playerWithdrawFlowLimitLogDetail', $playerWithdrawFlowLimitLogDetail);
    }

    /**
     * Update the specified PlayerWithdrawFlowLimitLogDetail in storage.
     *
     * @param  int              $id
     * @param UpdatePlayerWithdrawFlowLimitLogDetailRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePlayerWithdrawFlowLimitLogDetailRequest $request)
    {
        $playerWithdrawFlowLimitLogDetail = $this->playerWithdrawFlowLimitLogDetailRepository->findWithoutFail($id);

        if (empty($playerWithdrawFlowLimitLogDetail)) {
            Flash::error('Player Withdraw Flow Limit Log Detail not found');

            return redirect(route('playerWithdrawFlowLimitLogDetails.index'));
        }

        $playerWithdrawFlowLimitLogDetail = $this->playerWithdrawFlowLimitLogDetailRepository->update($request->all(), $id);

        Flash::success('Player Withdraw Flow Limit Log Detail updated successfully.');

        return redirect(route('playerWithdrawFlowLimitLogDetails.index'));
    }

    /**
     * Remove the specified PlayerWithdrawFlowLimitLogDetail from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $playerWithdrawFlowLimitLogDetail = $this->playerWithdrawFlowLimitLogDetailRepository->findWithoutFail($id);

        if (empty($playerWithdrawFlowLimitLogDetail)) {
            Flash::error('Player Withdraw Flow Limit Log Detail not found');

            return redirect(route('playerWithdrawFlowLimitLogDetails.index'));
        }

        $this->playerWithdrawFlowLimitLogDetailRepository->delete($id);

        Flash::success('Player Withdraw Flow Limit Log Detail deleted successfully.');

        return redirect(route('playerWithdrawFlowLimitLogDetails.index'));
    }
}
