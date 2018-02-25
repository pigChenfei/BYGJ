<?php
namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\CarrierAgentWithdrawLogDataTable;
use App\Http\Requests\Carrier\CreateCarrierAgentWithdrawLogRequest;
use App\Http\Requests\Carrier\UpdateCarrierAgentWithdrawLogRequest;
use App\Repositories\Carrier\CarrierAgentWithdrawLogRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Models\Log\CarrierAgentWithdrawLog;
use Carbon\Carbon;
use App\Models\CarrierPayChannel;
use App\Models\Log\CarrierQuotaConsumptionLog;
use App\Models\Log\AgentBearUndertakenLog;

class CarrierAgentWithdrawLogController extends AppBaseController
{

    /** @var  CarrierAgentWithdrawLogRepository */
    private $carrierAgentWithdrawLogRepository;

    public function __construct(CarrierAgentWithdrawLogRepository $carrierAgentWithdrawLogRepo)
    {
        $this->carrierAgentWithdrawLogRepository = $carrierAgentWithdrawLogRepo;
    }

    /**
     * Display a listing of the CarrierAgentWithdrawLog.
     *
     * @param Request $request
     * @return Response
     */
    public function index(CarrierAgentWithdrawLogDataTable $carrierAgentWithdrawLogDataTable)
    {
        return $carrierAgentWithdrawLogDataTable->render('Carrier.carrier_agent_withdraw_logs.index',
            [
                'title' => '代理取款记录'
            ]);
    }

    /**
     * Show the form for creating a new CarrierAgentWithdrawLog.
     *
     * @return Response
     */
    public function create()
    {
        return view('carrier_agent_withdraw_logs.create');
    }

    /**
     * Store a newly created CarrierAgentWithdrawLog in storage.
     *
     * @param CreateCarrierAgentWithdrawLogRequest $request
     *
     * @return Response
     */
    public function store(CreateCarrierAgentWithdrawLogRequest $request)
    {
        $input = $request->all();
        
        $carrierAgentWithdrawLog = $this->carrierAgentWithdrawLogRepository->create($input);
        
        Flash::success('Carrier Agent Withdraw Log saved successfully.');
        
        return redirect(route('carrierAgentWithdrawLogs.index'));
    }

    /**
     * Display the specified CarrierAgentWithdrawLog.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $carrierAgentWithdrawLog = $this->carrierAgentWithdrawLogRepository->findWithoutFail($id);
        
        if (empty($carrierAgentWithdrawLog)) {
            Flash::error('Carrier Agent Withdraw Log not found');
            
            return redirect(route('carrierAgentWithdrawLogs.index'));
        }
        
        return view('carrier_agent_withdraw_logs.show')->with('carrierAgentWithdrawLog', $carrierAgentWithdrawLog);
    }

    /**
     * Show the form for editing the specified CarrierAgentWithdrawLog.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $carrierAgentWithdrawLog = $this->carrierAgentWithdrawLogRepository->findWithoutFail($id);
        
        if (empty($carrierAgentWithdrawLog)) {
            Flash::error('Carrier Agent Withdraw Log not found');
            
            return redirect(route('carrierAgentWithdrawLogs.index'));
        }
        
        return view('carrier_agent_withdraw_logs.edit')->with('carrierAgentWithdrawLog', $carrierAgentWithdrawLog);
    }

    /**
     * Update the specified CarrierAgentWithdrawLog in storage.
     *
     * @param int $id
     * @param UpdateCarrierAgentWithdrawLogRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCarrierAgentWithdrawLogRequest $request)
    {
        $input = $request->all();
        // $agentWithdrawLog = $this->carrierAgentWithdrawLogRepository->with('agent')->findWithoutFail($id);
        $agentWithdrawLog = CarrierAgentWithdrawLog::with('agent')->findOrFail($id);
        if (empty($agentWithdrawLog)) {
            return $this->sendNotFoundResponse();
        }
        if ($request->get('status') == CarrierAgentWithdrawLog::STATUS_PAYED_OUT) {
            // 1 判断手续费承担方
            $feeAmount = $request->get('fee_amount');
            if ($feeAmount > $agentWithdrawLog->apply_amount) {
                return $this->sendErrorResponse('手续费超过申请金额,请检查');
            }
            $carrierPayChannel = CarrierPayChannel::findOrFail($request->get('carrier_pay_channel'));
            if ($carrierPayChannel->use_purpose != CarrierPayChannel::USED_FOR_WITHDRAW) {
                return $this->sendErrorResponse('该银行卡不能用于取款');
            }
            $feeUndertakenSide = $request->get('fee_bear_side');
            $agentUser = null;
            $carrierConsumptionRecord = new CarrierQuotaConsumptionLog();
            $carrierConsumptionRecord->related_pay_channel = $carrierPayChannel->id;
            $companyDepositFee = 0.00;
            switch ($feeUndertakenSide) {
                case 'player':
                    $input['finally_withdraw_amount'] = $agentWithdrawLog->apply_amount - $feeAmount;
                    $carrierConsumptionRecord->amount = - $input['finally_withdraw_amount'];
                    break;
                case 'agent':
                    $agentUser = new \App\Models\CarrierAgentUser();
                    $agentUser->id = $agentWithdrawLog->agent->id;
                    $agentUser->amount = $agentWithdrawLog->agent->amount -
                         ($agentWithdrawLog->apply_amount * ($feeAmount * 0.01));
                    $carrierConsumptionRecord->amount = - $agentWithdrawLog->apply_amount;
                    $input['finally_withdraw_amount'] = $agentWithdrawLog->apply_amount;
                    break;
                case 'company':
                    $companyDepositFee = $feeAmount;
                    $outAmt = bcadd($agentWithdrawLog->apply_amount, $feeAmount, 2);
                    $carrierConsumptionRecord->amount = - $outAmt;
                    $input['finally_withdraw_amount'] = $agentWithdrawLog->apply_amount;
            }
            if ($carrierPayChannel->balance < $carrierConsumptionRecord->amount) {
                return $this->sendErrorResponse('该卡余额不足');
            }
            $input['withdraw_succeed_at'] = Carbon::now();
            $input['operator'] = \WinwinAuth::carrierUser()->id;
            $carrierConsumptionRecord->carrier_id = \WinwinAuth::currentWebCarrier()->id;
            $carrierConsumptionRecord->consumption_source = '代理【' . $agentWithdrawLog->agent->username . '】取款：' .
                 $agentWithdrawLog->apply_amount;
            if (bccomp($companyDepositFee, 0, 2) > 0) {
                $carrierConsumptionRecord->consumption_source .= ',手续费：' . $companyDepositFee;
            }
            $carrierConsumptionRecord->consumption_source .= ',实际出账：' . $carrierConsumptionRecord->amount;
            $carrierConsumptionRecord->pay_channel_remain_amount = bcadd($carrierPayChannel->balance,
                $carrierConsumptionRecord->amount, 2);
            $carrierPayChannel->balance = $carrierConsumptionRecord->pay_channel_remain_amount;
            \DB::beginTransaction();
            try {
                // \WinwinAuth::carrierUser()->carrier->isRemainQuotaEnough();
                // 2, 更新运营商资金流水记录
                $carrierConsumptionRecord->save();
                // 3,更新银行卡数据
                $carrierPayChannel->update();
                // 4,更新会员资金流水
                $agentAccountLog = new \App\Models\Log\AgentAccountLog();
                $agentAccountLog->amount = $input['finally_withdraw_amount'];
                $agentAccountLog->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
                $agentAccountLog->agent_id = $agentWithdrawLog->agent->id;
                $agentAccountLog->fund_type = $agentAccountLog::FUND_TYPE_WITHDRAW;
                $agentAccountLog->operator_reviewer_id = \WinwinAuth::carrierUser()->id;
                $agentAccountLog->fund_source = '代理取款';
                $oldAmount = $agentWithdrawLog->agent->amount + $agentWithdrawLog->apply_amount;
                $newAmount = $agentWithdrawLog->agent->amount;
                $agentAccountLog->remark = '账户原余额：' . $oldAmount . ' 现余额：' . $newAmount . ' 手续费:' . $input['fee_amount'];
                $agentAccountLog->save();
                $agentUser && $agentUser->update();
                // $agentWithdrawLog->save($input);
                $this->carrierAgentWithdrawLogRepository->update($input, $id);
                \DB::commit();
            } catch (\Exception $e) {
                \DB::rollBack();
                return $this->sendErrorResponse($e->getMessage(), 500);
            }
        }
        return $this->sendSuccessResponse();
    }

    /**
     * Remove the specified CarrierAgentWithdrawLog from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $carrierAgentWithdrawLog = $this->carrierAgentWithdrawLogRepository->findWithoutFail($id);
        
        if (empty($carrierAgentWithdrawLog)) {
            Flash::error('Carrier Agent Withdraw Log not found');
            
            return redirect(route('carrierAgentWithdrawLogs.index'));
        }
        
        $this->carrierAgentWithdrawLogRepository->delete($id);
        
        Flash::success('Carrier Agent Withdraw Log deleted successfully.');
        
        return redirect(route('carrierAgentWithdrawLogs.index'));
    }

    /**
     *
     * @param $id
     * @return $this|Response
     */
    public function payModal($id)
    {
        $agentWithdrawLog = $this->carrierAgentWithdrawLogRepository->with(
            [
                'bankCard.bankType',
                'agent'
            ])->findWithoutFail($id);
        if (empty($agentWithdrawLog)) {
            return $this->sendNotFoundResponse();
        }
        return view('Carrier.carrier_agent_withdraw_logs.payModal')->with('agentWithdrawLog', $agentWithdrawLog);
    }

    /**
     * 显示拒绝申请的模态框
     *
     * @param $id
     * @return $this
     */
    public function refuseModal($id)
    {
        $agentWithdrawLog = $this->carrierAgentWithdrawLogRepository->findWithoutFail($id);
        if (empty($agentWithdrawLog)) {
            $this->sendNotFoundResponse();
        }
        return view('Carrier.carrier_agent_withdraw_logs.refuseModal')->with('agentWithdrawLog', $agentWithdrawLog);
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
            array_merge(
                [
                    'status' => 'required|in:' . CarrierAgentWithdrawLog::STATUS_REFUSED
                ], CarrierAgentWithdrawLog::$rules));
        $agentWithdrawLog = $this->carrierAgentWithdrawLogRepository->with('agent')->findWithoutFail($id);
        if (empty($agentWithdrawLog)) {
            return $this->sendNotFoundResponse();
        }
        $input = $request->all();
        $input['operator'] = \WinwinAuth::carrierUser()->id;
        $input['reviewed_at'] = Carbon::now();
        try {
            // 如果是拒绝, 那么冻结的处理的取款金额需要返回到主账户
            \DB::transaction(
                function () use ($input, $id, $agentWithdrawLog) {
                    $withdrawAmount = $agentWithdrawLog->apply_amount;
                    $agentWithdrawLog->agent->amount = $agentWithdrawLog->agent->amount + $withdrawAmount;
                    $agentWithdrawLog->agent->update();
                    $this->carrierAgentWithdrawLogRepository->update($input, $id);
                });
            return $this->sendSuccessResponse();
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage());
        }
    }
}
