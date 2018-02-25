<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 2017/4/13
 * Time: 下午8:40
 */
namespace App\Services;

use App\Models\Log\AgentBearUndertakenLog;
use App\Models\Log\CarrierQuotaConsumptionLog;
use App\Models\Log\PlayerAccountLog;
use App\Models\Log\PlayerRebateFinancialFlowNew;
use App\Models\Log\PlayerWithdrawFlowLimitLog;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class PassPlayerRebateFinancialFlowService
{

    /**
     *
     * @var Collection
     */
    private $rebateFinancialFlowLogs;

    public function __construct(Collection $rebateFinancialFlowLogs)
    {
        $this->rebateFinancialFlowLogs = $rebateFinancialFlowLogs;
    }

    public function handle()
    {
        try {
            \DB::transaction(
                function () {
                    $this->rebateFinancialFlowLogs->each(
                        function (PlayerRebateFinancialFlowNew $log) {
                            if ($log->rebate_financial_flow_amount <= 0) {
                                throw new \Exception('存在结算金额小于0的记录');
                            }
                            if ($log->rebate_manual_period_hours != 0 &&
                                 ((time() - strtotime($log->created_at)) > ($log->rebate_manual_period_hours * 3600))) {
                                throw new \Exception('存在时间过期的记录');
                            }
                            $log->is_already_settled = true;
                            $log->settled_at = Carbon::now();
                            $log->settled_type = 1;
                            // 检测该会员的代理商是否有洗码承担比例,如果代理完全承担洗码成本,那么运营商不需要扣除相应的额度;
                            if ($log->agent_out_amount > 0) {
                                $agentUnderTakeLog = new AgentBearUndertakenLog();
                                $agentUnderTakeLog->agent_id = $log->player->agent_id;
                                $agentUnderTakeLog->carrier_id = $log->carrier_id;
                                $agentUnderTakeLog->undertaken_type = AgentBearUndertakenLog::UNDERTAKEN_TYPE_BET_FINANCIAL_FLOW;
                                $agentUnderTakeLog->amount = $log->agent_out_amount;
                                $agentUnderTakeLog->company_amount = $log->company_out_amount;
                                $agentUnderTakeLog->save();
                            }
                            // if ($log->company_out_amount > 0) {
                            // $carrierQuotaLog = new CarrierQuotaConsumptionLog();
                            // $carrierQuotaLog->carrier_id = $log->carrier_id;
                            // $carrierQuotaLog->amount = - $log->company_out_amount;
                            // $carrierQuotaLog->consumption_source = '玩家洗码成本';
                            // $carrierQuotaLog->remark = '分担玩家洗码成本';
                            // $log->player->carrier->checkRemainQuotaEnough($log->company_out_amount);
                            // $carrierQuotaLog->pay_channel_remain_amount = $log->player->carrier->remain_quota - $log->company_out_amount;
                            // $carrierQuotaLog->save();
                            // if ($carrierQuotaLog) {
                            // $log->player->carrier->remain_quota = $carrierQuotaLog->pay_channel_remain_amount;
                            // $log->player->carrier->update();
                            // }
                            // }
                            $log->update();
                            $log->player->main_account_amount += $log->rebate_financial_flow_amount;
                            $log->player->update();
                            // 玩家资金记录新增;
                            $playerAccountLog = new PlayerAccountLog();
                            $playerAccountLog->amount = $log->rebate_financial_flow_amount;
                            $playerAccountLog->carrier_id = $log->carrier_id;
                            $playerAccountLog->player_id = $log->player_id;
                            $playerAccountLog->fund_source = '玩家洗码';
                            $playerAccountLog->remark = $log->rebate_type == 1 ? '客服发放结算' : '玩家自助领取';
                            $playerAccountLog->operator_reviewer_id = \WinwinAuth::carrierUser() ? \WinwinAuth::carrierUser()->id : null;
                            $playerAccountLog->fund_type = PlayerAccountLog::FUND_TYPE_FINANCIAL_FLOW;
                            $playerAccountLog->main_game_plat_id = $log->gamePlat->main_game_plat_id;
                            $playerAccountLog->save();
                            
                            // 新增玩家取款限制, 默认1倍流水限制;
                            $playerWithdrawFlowLimitLog = new PlayerWithdrawFlowLimitLog();
                            $playerWithdrawFlowLimitLog->carrier_id = $log->carrier_id;
                            $playerWithdrawFlowLimitLog->player_account_log = $playerAccountLog->log_id;
                            $playerWithdrawFlowLimitLog->player_id = $log->player_id;
                            $playerWithdrawFlowLimitLog->limit_amount = $log->rebate_financial_flow_amount;
                            $playerWithdrawFlowLimitLog->limit_type = $log->rebate_type == 1 ? PlayerWithdrawFlowLimitLog::LIMIT_TYPE_AUTO_REBATE_FINANCIAL_FLOW : PlayerWithdrawFlowLimitLog::LIMIT_TYPE_MANUAL_REBATE_FINANCIAL_FLOW;
                            $playerWithdrawFlowLimitLog->save();
                        });
                });
        } catch (\Exception $e) {
            throw $e;
        }
    }
}