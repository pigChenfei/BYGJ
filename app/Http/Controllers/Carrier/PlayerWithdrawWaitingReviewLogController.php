<?php

namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\PlayerWithdrawLogDataTable;
use App\DataTables\Carrier\PlayerWithdrawWaitingReviewLogDataTable;
use App\Http\Requests\Carrier;
use App\Http\Requests\Carrier\CreatePlayerWithdrawLogRequest;
use App\Http\Requests\Carrier\UpdatePlayerWithdrawWaitingReviewLogRequest;
use App\Models\Log\PlayerWithdrawFlowLimitLog;
use App\Models\Log\PlayerWithdrawLog;
use App\Repositories\Carrier\PlayerWithdrawLogRepository;
use Carbon\Carbon;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class PlayerWithdrawWaitingReviewLogController extends AppBaseController
{
    /** @var  PlayerWithdrawLogRepository */
    private $playerWithdrawLogRepository;

    public function __construct(PlayerWithdrawLogRepository $playerWithdrawLogRepo)
    {
        $this->playerWithdrawLogRepository = $playerWithdrawLogRepo;
    }

    /**
     * Display a listing of the PlayerWithdrawLog.
     *
     * @param PlayerWithdrawWaitingReviewLogDataTable $playerWithdrawLogDataTable
     * @return Response
     */
    public function index(PlayerWithdrawWaitingReviewLogDataTable $playerWithdrawLogDataTable)
    {
        return $playerWithdrawLogDataTable->render('Carrier.player_withdraw_waiting_review_logs.index');
    }

    /**
     * Show the form for creating a new PlayerWithdrawLog.
     *
     * @return Response
     */
    public function create()
    {
        return view('Carrier.player_withdraw_waiting_review_logs.create');
    }

    /**
     * Store a newly created PlayerWithdrawLog in storage.
     *
     * @param CreatePlayerWithdrawLogRequest $request
     *
     * @return Response
     */
    public function store(CreatePlayerWithdrawLogRequest $request)
    {
        $input = $request->all();

        $playerWithdrawLog = $this->playerWithdrawLogRepository->create($input);

        Flash::success('Player Withdraw Log saved successfully.');

        return redirect(route('playerWithdrawLogs.index'));
    }

    /**
     * Display the specified PlayerWithdrawLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $playerWithdrawLog = $this->playerWithdrawLogRepository->findWithoutFail($id);
        if (empty($playerWithdrawLog)) {
            $this->sendNotFoundResponse();
        }
        $withDrawFlowRecords = PlayerWithdrawFlowLimitLog::byPlayerId($playerWithdrawLog->player_id)->with(['limitGamePlats.gamePlat','carrierActivity','limitFlowCompleteDetail.game'])->orderBy('created_at','desc')->get();

        return view('Carrier.player_withdraw_waiting_review_logs.show')->with('playerWithdrawLog', $playerWithdrawLog)->with('withDrawFlowRecords', $withDrawFlowRecords);
    }

    public function refuseModal($id){
        $playerWithdrawLog = $this->playerWithdrawLogRepository->findWithoutFail($id);
        if (empty($playerWithdrawLog)) {
            $this->sendNotFoundResponse();
        }
        return view('Carrier.player_withdraw_waiting_review_logs.refuseModal')->with('playerWithdrawLog', $playerWithdrawLog);

    }
    /**
     * Show the form for editing the specified PlayerWithdrawLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $playerWithdrawLog = $this->playerWithdrawLogRepository->findWithoutFail($id);

        if (empty($playerWithdrawLog)) {
            Flash::error('Player Withdraw Log not found');

            return redirect(route('playerWithdrawLogs.index'));
        }

        return view('Carrier.player_withdraw_waiting_review_logs.edit')->with('playerWithdrawLog', $playerWithdrawLog);
    }

    /**
     * Update the specified PlayerWithdrawLog in storage.
     *
     * @param  int              $id
     * @param UpdatePlayerWithdrawWaitingReviewLogRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePlayerWithdrawWaitingReviewLogRequest $request)
    {
        $playerWithdrawLog = $this->playerWithdrawLogRepository->with('player')->findWithoutFail($id);
        if (empty($playerWithdrawLog)) {
           return $this->sendNotFoundResponse();
        }
        $input = $request->all();
        if($request->get('status') == PlayerWithdrawLog::STATUS_REFUSED || $request->get('status') == PlayerWithdrawLog::STATUS_SUCCEED_REVIEWED){
            $input['operator'] = \WinwinAuth::carrierUser()->id;
            $input['reviewed_at'] = Carbon::now();
        }
        try{
            //如果是拒绝, 那么冻结的处理的取款金额需要返回到主账户
            \DB::transaction(function () use($input,$id,$playerWithdrawLog){
                $withdrawAmount = $playerWithdrawLog->apply_amount;
                $playerWithdrawLog->player->main_account_amount += $withdrawAmount;
                $playerWithdrawLog->player->frozen_main_acount_amount -= $withdrawAmount;
                $playerWithdrawLog->player->update();
                $this->playerWithdrawLogRepository->update($input, $id);
            });
            return $this->sendSuccessResponse();
        }catch (\Exception $e){
            return $this->sendErrorResponse($e->getMessage());
        }

    }

    public function resetWithDrawFlowRecord($id){
        $playerWithdrawFlowLimitLog = PlayerWithdrawFlowLimitLog::findOrFail($id);
        $playerWithdrawFlowLimitLog->is_finished = true;
        if(!$playerWithdrawFlowLimitLog->operator_id){
            $playerWithdrawFlowLimitLog->operator_id = \WinwinAuth::carrierUser()->id;
        }
        $playerWithdrawFlowLimitLog->update();
        return $this->sendSuccessResponse();
    }


    /**
     * Remove the specified PlayerWithdrawLog from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $playerWithdrawLog = $this->playerWithdrawLogRepository->findWithoutFail($id);

        if (empty($playerWithdrawLog)) {
            Flash::error('Player Withdraw Log not found');

            return redirect(route('playerWithdrawLogs.index'));
        }

        $this->playerWithdrawLogRepository->delete($id);

        Flash::success('Player Withdraw Log deleted successfully.');

        return redirect(route('playerWithdrawLogs.index'));
    }
}
