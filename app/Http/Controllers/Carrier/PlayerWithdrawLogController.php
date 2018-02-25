<?php
namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\PlayerWithdrawLogDataTable;
use App\Http\Requests\Carrier;
use App\Http\Requests\Carrier\CreatePlayerWithdrawLogRequest;
use App\Http\Requests\Carrier\UpdatePlayerWithdrawLogRequest;
use App\Models\CarrierPayChannel;
use App\Models\Log\AgentBearUndertakenLog;
use App\Models\Log\CarrierQuotaConsumptionLog;
use App\Models\Log\PlayerAccountLog;
use App\Models\Log\PlayerWithdrawFlowLimitLog;
use App\Models\Log\PlayerWithdrawLog;
use App\Repositories\Carrier\PlayerWithdrawLogRepository;
use Carbon\Carbon;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Response;
use App\DataTables\Carrier\PlayerWithdrawLogVerifyDataTable;

class PlayerWithdrawLogController extends AppBaseController
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
     * @param PlayerWithdrawLogDataTable $playerWithdrawLogDataTable
     * @return Response
     */
    public function index(PlayerWithdrawLogDataTable $playerWithdrawLogDataTable)
    {
        return $playerWithdrawLogDataTable->render('Carrier.player_withdraw_logs.index');
    }

    /**
     * Display a listing of the PlayerWithdrawLog.
     *
     * @param PlayerWithdrawLogDataTable $playerWithdrawLogDataTable
     * @return Response
     */
    public function verify(PlayerWithdrawLogVerifyDataTable $playerWithdrawLogDataTable)
    {
        return $playerWithdrawLogDataTable->render('Carrier.player_withdraw_logs.verify');
    }

    /**
     * Show the form for creating a new PlayerWithdrawLog.
     *
     * @return Response
     */
    public function create()
    {
        return view('Carrier.player_withdraw_logs.create');
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
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $playerWithdrawLog = $this->playerWithdrawLogRepository->findWithoutFail($id);
        if (empty($playerWithdrawLog)) {
            $this->sendNotFoundResponse();
        }
        $withDrawFlowRecords = PlayerWithdrawFlowLimitLog::byPlayerId($playerWithdrawLog->player_id)->with(
            [
                'limitGamePlats.gamePlat',
                'carrierActivity',
                'limitFlowCompleteDetail.game'
            ])
            ->orderBy('created_at', 'desc')
            ->get();
        $unfinishedAmount = 0;
        $unfinishedWithPlatAmount = 0;
        foreach ($withDrawFlowRecords as $withDrawFlowRecord) {
            if ($withDrawFlowRecord->is_finished == 0) {
                $unfinishedAmount = bcadd($unfinishedAmount,
                    bcsub($withDrawFlowRecord->limit_amount, $withDrawFlowRecord->complete_limit_amount, 2), 2);
                if (count($withDrawFlowRecord->limitGamePlats) <= 0) {
                    $unfinishedWithPlatAmount = bcadd($unfinishedWithPlatAmount,
                        bcsub($withDrawFlowRecord->limit_amount, $withDrawFlowRecord->complete_limit_amount, 2), 2);
                }
            }
        }
        return view('Carrier.player_withdraw_logs.show')->with('playerWithdrawLog', $playerWithdrawLog)
            ->with('withDrawFlowRecords', $withDrawFlowRecords)
            ->with('unfinishedAmount', $unfinishedAmount)
            ->with('unfinishedWithPlatAmount', $unfinishedWithPlatAmount);
    }

    /**
     *
     * @param $id
     * @return $this|Response
     */
    public function payModal($id)
    {
        $playerWithdrawLog = $this->playerWithdrawLogRepository->with(
            [
                'bankCard.bankType',
                'player'
            ])->findWithoutFail($id);
        if (empty($playerWithdrawLog)) {
            return $this->sendNotFoundResponse();
        }
        if ($playerWithdrawLog->player) {
            $playerTodayWithdrawCount = PlayerWithdrawLog::accountOut()->byPlayerId(
                $playerWithdrawLog->player->player_id)
                ->withdrawSucceedAtToday()
                ->count();
        } else {
            $playerTodayWithdrawCount = 0;
        }
        $banks = CarrierPayChannel::available()->withdrawPurpose()
            ->with('payChannel.payChannelType', 'levelBankcard')
            ->whereHas('levelBankcard',
            function ($query) use ($playerWithdrawLog) {
                $query->where('carrier_player_level_id', $playerWithdrawLog->player->player_level_id);
            })
            ->get()
            ->filter(
            function ($element) {
                return $element->payChannel->payChannelType->isCompanyBankTransfer();
            });
        return view('Carrier.player_withdraw_logs.payModal')->with(
            [
                'playerWithdrawLog' => $playerWithdrawLog,
                'playerTodayWithdrawCount' => $playerTodayWithdrawCount,
                'banks' => $banks
            ]);
    }

    /**
     * Show the form for editing the specified PlayerWithdrawLog.
     *
     * @param int $id
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
        
        return view('Carrier.player_withdraw_logs.edit')->with('playerWithdrawLog', $playerWithdrawLog);
    }

    /**
     * Update the specified PlayerWithdrawLog in storage.
     *
     * @param int $id
     * @param UpdatePlayerWithdrawLogRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePlayerWithdrawLogRequest $request)
    {
        $input = $request->all();
        $input['operator'] = \WinwinAuth::carrierUser()->id;
        $input['reviewed_at'] = Carbon::now();
        $playerWithdrawLog = $this->playerWithdrawLogRepository->with('player')->findWithoutFail($id);
        if (empty($playerWithdrawLog)) {
            return $this->sendNotFoundResponse();
        }
        if ($request->get('status') == PlayerWithdrawLog::STATUS_PAYED_OUT) {
            if ($playerWithdrawLog->status != PlayerWithdrawLog::STATUS_WAITING_REVIEWED) {
                return $this->sendErrorResponse('该取款记录已经审核过了');
            }
            // 1 判断手续费承担方
            $feeAmount = $request->get('fee_amount');
            if ($feeAmount > $playerWithdrawLog->apply_amount) {
                return $this->sendErrorResponse('手续费超过申请金额,请检查');
            }
            $carrierPayChannel = CarrierPayChannel::findOrFail($request->get('carrier_pay_channel'));
            if ($carrierPayChannel->use_purpose != CarrierPayChannel::USED_FOR_WITHDRAW) {
                return $this->sendErrorResponse('该银行卡不能用于取款');
            }
            $feeUndertakenSide = $request->get('fee_bear_side');
            $agentUndertakenRecord = null;
            $carrierConsumptionRecord = new CarrierQuotaConsumptionLog();
            $carrierConsumptionRecord->carrier_id = $playerWithdrawLog->player->carrier_id;
            $carrierConsumptionRecord->related_pay_channel = $carrierPayChannel->id;
            $companyDepositFee = 0.00;
            switch ($feeUndertakenSide) {
                case 'player':
                    $input['finally_withdraw_amount'] = $playerWithdrawLog->apply_amount - $feeAmount;
                    $carrierConsumptionRecord->amount = - $input['finally_withdraw_amount'];
                    break;
                case 'agent':
                    if (! $playerWithdrawLog->player->agent->isCarrierDefaultAgent() &&
                         $playerWithdrawLog->player->agent->agentLevel->isCommissionAgent()) {
                        $agentUndertakenRecord = new AgentBearUndertakenLog();
                        $agentUndertakenRecord->agent_id = $playerWithdrawLog->player->agent->id;
                        $agentUndertakenRecord->carrier_id = $playerWithdrawLog->player->carrier_id;
                        $agentUndertakenRecord->amount = $feeAmount;
                        $agentUndertakenRecord->undertaken_type = AgentBearUndertakenLog::UNDERTAKEN_TYPE_WITHDRAW_FEE;
                        
                        $carrierConsumptionRecord->amount = - $playerWithdrawLog->apply_amount;
                        $input['finally_withdraw_amount'] = $playerWithdrawLog->apply_amount;
                        break;
                    }
                case 'company':
                    $companyDepositFee = $feeAmount;
                    $outAmt = bcadd($playerWithdrawLog->apply_amount, $feeAmount, 2);
                    $carrierConsumptionRecord->amount = - $outAmt;
                    $input['finally_withdraw_amount'] = $playerWithdrawLog->apply_amount;
            }
            // if(\WinwinAuth::carrierUser()->carrier->isRemainQuotaEnough($carrierConsumptionRecord->amount) == false){
            // return $this->sendErrorResponse('运营商额度不足');
            // }
            if ($carrierPayChannel->balance < $carrierConsumptionRecord->amount) {
                return $this->sendErrorResponse('该卡余额不足');
            }
            $input['withdraw_succeed_at'] = Carbon::now();
            $carrierConsumptionRecord->consumption_source = '会员【' . $playerWithdrawLog->player->user_name . '】取款：' .
                 $playerWithdrawLog->apply_amount;
            if (bccomp($companyDepositFee, 0, 2) > 0) {
                $carrierConsumptionRecord->consumption_source .= ',手续费：' . $companyDepositFee;
            }
            $carrierConsumptionRecord->consumption_source .= ',实际出账：' . $carrierConsumptionRecord->amount;
            $carrierConsumptionRecord->pay_channel_remain_amount = bcadd($carrierPayChannel->balance,
                $carrierConsumptionRecord->amount, 2);
            $carrierPayChannel->balance = $carrierConsumptionRecord->pay_channel_remain_amount;
            try {
                // \WinwinAuth::carrierUser()->carrier->isRemainQuotaEnough();
                \DB::transaction(
                    function () use ($id, $carrierPayChannel, $input, $carrierConsumptionRecord, $playerWithdrawLog,
                    $agentUndertakenRecord) {
                        // 2, 更新运营商资金流水记录
                        $carrierConsumptionRecord->save();
                        // 3,更新银行卡数据
                        $carrierPayChannel->update();
                        // 4,更新会员资金流水
                        $playerAccountLog = new PlayerAccountLog();
                        $playerAccountLog->amount = $input['finally_withdraw_amount'];
                        $playerAccountLog->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
                        $playerAccountLog->player_id = $playerWithdrawLog->player->player_id;
                        $playerAccountLog->fund_type = PlayerAccountLog::FUND_TYPE_WITHDRAW;
                        $playerAccountLog->operator_reviewer_id = \WinwinAuth::carrierUser()->id;
                        $playerAccountLog->fund_source = '会员取款';
                        $oldAmount = $playerWithdrawLog->player->main_account_amount +
                             $playerWithdrawLog->player->frozen_main_account_amount;
                        $newAmount = $oldAmount - $playerWithdrawLog->apply_amount;
                        $playerAccountLog->remark = '主账户原余额： ' . $oldAmount . ' 现余额： ' . $newAmount .
                             ($input['fee_bear_side'] == 'player' ? ' 手续费:' . $input['fee_amount'] : '');
                        // 5,更新会员主账户余额
                        // 6,玩家冻结余额减掉;
                        $playerWithdrawLog->player->frozen_main_account_amount -= $playerWithdrawLog->apply_amount;
                        $playerWithdrawLog->player->update();
                        $playerAccountLog->save();
                        // 6如果有代理承担记录,那么新建代理承担记录;
                        $agentUndertakenRecord && $agentUndertakenRecord->save();
                        $this->playerWithdrawLogRepository->update($input, $id);
                        // 7删除取款之前已完成的流水;
                        PlayerWithdrawFlowLimitLog::byPlayerId($playerWithdrawLog->player->player_id)->finished()
                            ->where('created_at', '<=', Carbon::now()->toDateString())
                            ->delete();
                    });
                
                return $this->sendSuccessResponse();
            } catch (\Exception $e) {
                return $this->sendErrorResponse($e->getMessage(), 500);
            }
        }
        return $this->sendErrorResponse('不合法的审核请求');
    }

    /**
     * 显示拒绝申请的模态框
     *
     * @param $id
     * @return $this
     */
    public function refuseModal($id)
    {
        $playerWithdrawLog = $this->playerWithdrawLogRepository->findWithoutFail($id);
        if (empty($playerWithdrawLog)) {
            $this->sendNotFoundResponse();
        }
        return view('Carrier.player_withdraw_logs.refuseModal')->with('playerWithdrawLog', $playerWithdrawLog);
    }

    /**
     * 拒绝取款申请
     *
     * @param $id
     * @param Request $request
     * @return mixed|Response
     */
    public function refuseWithdrawApply($id, Request $request)
    {
        $this->validate($request,
            array_merge([
                'status' => 'required|in:' . PlayerWithdrawLog::STATUS_REFUSED
            ], PlayerWithdrawLog::$rules));
        $playerWithdrawLog = $this->playerWithdrawLogRepository->with('player')->findWithoutFail($id);
        if (empty($playerWithdrawLog)) {
            return $this->sendNotFoundResponse();
        }
        $input = $request->all();
        $input['operator'] = \WinwinAuth::carrierUser()->id;
        $input['reviewed_at'] = Carbon::now();
        try {
            // 如果是拒绝, 那么冻结的处理的取款金额需要返回到主账户
            \DB::transaction(
                function () use ($input, $id, $playerWithdrawLog) {
                    $withdrawAmount = $playerWithdrawLog->apply_amount;
                    $playerWithdrawLog->player->main_account_amount += $withdrawAmount;
                    $playerWithdrawLog->player->frozen_main_account_amount -= $withdrawAmount;
                    $playerWithdrawLog->player->update();
                    $this->playerWithdrawLogRepository->update($input, $id);
                });
            return $this->sendSuccessResponse();
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    /**
     * 重置流水限制
     *
     * @param $id
     * @return mixed
     */
    public function resetWithdrawFlowRecord($id)
    {
        $playerWithdrawFlowLimitLog = PlayerWithdrawFlowLimitLog::findOrFail($id);
        $playerWithdrawFlowLimitLog->is_finished = true;
        if (! $playerWithdrawFlowLimitLog->operator_id) {
            $playerWithdrawFlowLimitLog->operator_id = \WinwinAuth::carrierUser()->id;
        }
        $playerWithdrawFlowLimitLog->update();
        return $this->sendSuccessResponse();
    }

    /**
     * Remove the specified PlayerWithdrawLog from storage.
     *
     * @param int $id
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
