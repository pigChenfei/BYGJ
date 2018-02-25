<?php

namespace App\Http\Controllers\Carrier;

use App\Http\Requests\Carrier\CreateAgentAccountAdjustLogRequest;
use App\Http\Requests\Carrier\UpdateAgentAccountAdjustLogRequest;
use App\Repositories\Carrier\AgentAccountAdjustLogRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Models\Log\AgentAccountLog;
use App\Models\CarrierAgentUser;
use App\Models\Log\CarrierQuotaConsumptionLog;
use App\Models\Log\AgentAccountAdjustLog;
use App\Models\CarrierPayChannel;
use App\DataTables\Carrier\AgentAccountAdjustLogDataTable;

class AgentAccountAdjustLogController extends AppBaseController
{
    /** @var  AgentAccountAdjustLogRepository */
    private $agentAccountAdjustLogRepository;

    public function __construct(AgentAccountAdjustLogRepository $agentAccountAdjustLogRepo)
    {
        $this->agentAccountAdjustLogRepository = $agentAccountAdjustLogRepo;
    }

    /**
     * Display a listing of the AgentAccountAdjustLog.
     *
     * @param Request $request
     * @return Response
     */
    public function index(AgentAccountAdjustLogDataTable $agentAccountAdjustLogDataTable)
    {
        return $agentAccountAdjustLogDataTable->render('Carrier.carrier_account_adjusts_log.index');
    }

    /**
     * Show the form for creating a new AgentAccountAdjustLog.
     *
     * @return Response
     */
    public function create()
    {
        return view('agent_account_adjust_logs.create');
    }

    /**
     * Store a newly created AgentAccountAdjustLog in storage.
     *
     * @param CreateAgentAccountAdjustLogRequest $request
     *
     * @return Response
     */
    public function store(CreateAgentAccountAdjustLogRequest $request)
    {
        //调整会员余额
        //1, 如果需要记账, 调整运营商银行卡余额
        //5, 更新会员账户余额
        if($request->get('amount') == 0){
            return $this->sendErrorResponse("请设置调整余额");
        }
        try{
            \DB::transaction(function () use ($request){
                $input = $request->all();
                $carrierAgentUser = CarrierAgentUser::findOrFail($input['agent_id']);
                $agentAccountLog = new AgentAccountLog();
                $carrierConsumptionLog = new CarrierQuotaConsumptionLog();
                //检测调整类型
                if($request->get('adjust_type') == AgentAccountAdjustLog::ADJUST_TYPE_DEPOSIT){//存款
                    $agentAccountLog->fund_type = $request->get('adjust_is_plus') == 1 ? AgentAccountLog::FUND_TYPE_DEPOSIT : AgentAccountLog::FUND_TYPE_WITHDRAW;
                    $carrierConsumptionLog->consumption_source = '调整代理存款账户余额';
                    $agentAccountLog->fund_source = '客服调整代理账户余额';
                }else if($request->get('adjust_type') == AgentAccountAdjustLog::ADJUST_TYPE_COMMISSION){//佣金
                    $agentAccountLog->fund_type = $request->get('adjust_is_plus') == 1 ? AgentAccountLog::FUND_TYPE_DEPOSIT : AgentAccountLog::FUND_TYPE_WITHDRAW;
                    $carrierConsumptionLog->consumption_source = '调整代理佣金账号余额';
                    $agentAccountLog->fund_source = '客服调整代理账户余额';
                }
                //调整代理账户金额
                $input['amount'] = $request->get('amount') * ($request->get('adjust_is_plus') == 1 ? 1 : -1);
                //记账处理
                if($recordPayChannel = $request->get('amount_record_pay_channel')){
                    $payChannel = CarrierPayChannel::available()->findOrFail($recordPayChannel);
                    $payChannel->balance += $input['amount'];
                    if($payChannel->balance < 0){
                        throw new \Exception('该银行卡余额不足');
                    }
                    $carrierConsumptionLog->amount = $input['amount'];
                    $carrierConsumptionLog->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
                    $carrierConsumptionLog->pay_channel_remain_amount = $payChannel->balance;
                    $carrierConsumptionLog->related_pay_channel = $payChannel->id;
                    $carrierConsumptionLog->save();
                    $payChannel->update();
                }
                
                //处理人员
                $input['operator'] = \WinwinAuth::carrierUser()->id;
                $input['carrier_id'] = \WinwinAuth::carrierUser()->carrier_id;
                $this->agentAccountAdjustLogRepository->create($input);
                //新增账户记录
                $agentAccountLog->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
                $agentAccountLog->agent_id  = $input['agent_id'];
                $agentAccountLog->amount     = abs($input['amount']);
                $agentAccountLog->operator_reviewer_id = \WinwinAuth::carrierUser()->id;
                //更新代理账户余额
                $oldMainAmount = $carrierAgentUser->amount;
                $carrierAgentUser->amount += $input['amount'];
                $agentAccountLog->remark = '主账户原余额： '.$oldMainAmount.' 现余额： '.$carrierAgentUser->amount;
                $agentAccountLog->save();
                $carrierAgentUser->update();
            });
            return $this->sendSuccessResponse();
        }catch (\Exception $e){
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    /**
     * Display the specified AgentAccountAdjustLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $agentAccountAdjustLog = $this->agentAccountAdjustLogRepository->findWithoutFail($id);

        if (empty($agentAccountAdjustLog)) {
            Flash::error('Agent Account Adjust Log not found');

            return redirect(route('agentAccountAdjustLogs.index'));
        }

        return view('agent_account_adjust_logs.show')->with('agentAccountAdjustLog', $agentAccountAdjustLog);
    }

    /**
     * Show the form for editing the specified AgentAccountAdjustLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $agentAccountAdjustLog = $this->agentAccountAdjustLogRepository->findWithoutFail($id);

        if (empty($agentAccountAdjustLog)) {
            Flash::error('Agent Account Adjust Log not found');

            return redirect(route('agentAccountAdjustLogs.index'));
        }

        return view('agent_account_adjust_logs.edit')->with('agentAccountAdjustLog', $agentAccountAdjustLog);
    }

    /**
     * Update the specified AgentAccountAdjustLog in storage.
     *
     * @param  int              $id
     * @param UpdateAgentAccountAdjustLogRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAgentAccountAdjustLogRequest $request)
    {
        $agentAccountAdjustLog = $this->agentAccountAdjustLogRepository->findWithoutFail($id);

        if (empty($agentAccountAdjustLog)) {
            Flash::error('Agent Account Adjust Log not found');

            return redirect(route('agentAccountAdjustLogs.index'));
        }

        $agentAccountAdjustLog = $this->agentAccountAdjustLogRepository->update($request->all(), $id);

        Flash::success('Agent Account Adjust Log updated successfully.');

        return redirect(route('agentAccountAdjustLogs.index'));
    }

    /**
     * Remove the specified AgentAccountAdjustLog from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $agentAccountAdjustLog = $this->agentAccountAdjustLogRepository->findWithoutFail($id);

        if (empty($agentAccountAdjustLog)) {
            Flash::error('Agent Account Adjust Log not found');

            return redirect(route('agentAccountAdjustLogs.index'));
        }

        $this->agentAccountAdjustLogRepository->delete($id);

        Flash::success('Agent Account Adjust Log deleted successfully.');

        return redirect(route('agentAccountAdjustLogs.index'));
    }
}
