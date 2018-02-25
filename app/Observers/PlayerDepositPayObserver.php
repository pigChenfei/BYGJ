<?php
namespace App\Observers;

use App\Jobs\JudgePlayerHasNotAutoJoinActivity;
use App\Models\Log\PlayerDepositPayLog;
use App\Jobs\PlayerUpgradeLevelHandle;
use App\Helpers\Caches\CarrierInfoCacheHelper;
use App\Helpers\Caches\PlayerInfoCacheHelper;
use App\Notifications\CarrierPlayerDepositNotification;
use App\Models\CarrierPayChannel;
use App\Models\Log\CarrierQuotaConsumptionLog;
use Carbon\Carbon;
use App\Models\Log\AgentBearUndertakenLog;
use App\Models\Log\PlayerAccountLog;
use App\Models\Log\PlayerWithdrawFlowLimitLog;
use App\Jobs\JudgePlayerHasAutoJoinActivity;

class PlayerDepositPayObserver
{

    private $log;

    public function created(PlayerDepositPayLog $log)
    {
        $this->log = $log;
        if ($this->log->status == PlayerDepositPayLog::ORDER_STATUS_PAY_SUCCEED) {
            // 玩家升级队列处理
            dispatch(new PlayerUpgradeLevelHandle(PlayerInfoCacheHelper::getPlayerCacheInfoById($this->log->player_id)));
        }
        if ($this->log->status == PlayerDepositPayLog::ORDER_STATUS_WAITING_REVIEW) {
            // 通知当前存款用户的运营商;
            CarrierInfoCacheHelper::getCachedCarrierInfoByCarrierId($this->log->carrier_id)->notify(
                new CarrierPlayerDepositNotification($this->log));
        }
    }

    public function updated(PlayerDepositPayLog $log)
    {
        $this->log = $log;
        if ($this->log->status == PlayerDepositPayLog::ORDER_STATUS_PAY_SUCCEED && $this->log->benefit_amount == 0.00 &&
             $this->log->finally_amount == 0.00) {
            try {
                \DB::transaction(
                    function () {
                        $default_preferential_ratio = bcdiv($this->log->carrierPayChannel->default_preferential_ratio,
                            100, 5);
                        // 主账户原余额
                        $playerOldAmt = $this->log->player->main_account_amount;
                        // 计算存款优惠,更新玩家主账户余额
                        $depositBenefit = bcmul($this->log->amount, $default_preferential_ratio, 2);
                        $this->log->benefit_amount = $depositBenefit; // 优惠总金额
                        $this->log->finally_amount = bcadd($this->log->amount, $depositBenefit, 2); // 实际到账金额
                        $fee_ratio = bcdiv($this->log->carrierPayChannel->fee_ratio, 100, 5); // 手续费比列
                        $depositFee = bcmul($this->log->amount, $fee_ratio, 2); // 计算手续费
                        $companyDepositFee = $playerDespositFee = $playerAmtAfterBenefit = 0.00;
                        $this->log->fee_amount = $depositFee;
                        $feeBearType = $this->log->carrierPayChannel->fee_bear_id;
                        $carrierPayChannel = $this->log->carrierPayChannel;
                        // 如果是公司承担手续费或者代理是公司默认代理,或者代理不是佣金代理, 那么还是由公司承担手续费
                        if ($feeBearType != CarrierPayChannel::FEE_BEAR_PLAYER && ($feeBearType ==
                             CarrierPayChannel::FEE_BEAR_COMPANY || $this->log->player->agent->isCarrierDefaultAgent() ||
                             $this->log->player->agent->agentLevel->isCommissionAgent() == false)) {
                            // 如果是公司承担手续费, 那么需要增加公司资金流水
                            if (bccomp($depositFee, 0.00, 2) > 0) {
                                $companyDepositFee = $depositFee;
                            }
                        } else if ($feeBearType == CarrierPayChannel::FEE_BEAR_AGENT &&
                             $this->log->player->agent->isCarrierDefaultAgent() == false &&
                             $this->log->player->agent->agentLevel->isCommissionAgent()) {
                            // 如果是代理承担并且是佣金代理才能计算手续费承担数据
                            // 如果是代理承担手续费,那么需要新增代理承担记录
                            $agentUnderTakeLog = new AgentBearUndertakenLog();
                            // 获取最大承担比例;
                            $conf = $this->log->player->agent->agentLevel->commissionAgentConf;
                            // 计算手续费承担比例
                            $deposit_fee_undertake_ratio = bcdiv($conf->deposit_fee_undertake_ratio, 100, 5);
                            $agentUnderTakeAmount = bcmul($depositFee, $deposit_fee_undertake_ratio, 2);
                            // 计算运营商承担数据
                            $carrierUnderTakeAmount = 0.00;
                            if (bccomp($conf->deposit_fee_undertake_max, 0.00, 2) > 0 &&
                                 bccomp($agentUnderTakeAmount, $conf->deposit_fee_undertake_max, 2) > 0) {
                                $agentUnderTakeAmount = $conf->deposit_fee_undertake_max;
                            }
                            $carrierUnderTakeAmount = bcsub($depositFee, $agentUnderTakeAmount, 2);
                            // 公司承担手续费
                            if (bccomp($carrierUnderTakeAmount, 0.00, 2) > 0) {
                                $companyDepositFee = $carrierUnderTakeAmount;
                            }
                            $agentUnderTakeLog->amount = $agentUnderTakeAmount;
                            $agentUnderTakeLog->company_amount = $carrierUnderTakeAmount;
                            $agentUnderTakeLog->agent_id = $this->log->player->agent->id;
                            $agentUnderTakeLog->carrier_id = $this->log->player->carrier_id;
                            $agentUnderTakeLog->undertaken_type = AgentBearUndertakenLog::UNDERTAKEN_TYPE_DEPOSIT_FEE;
                            bccomp($agentUnderTakeLog->amount, 0.00, 2) > 0 && $agentUnderTakeLog->save();
                        } else if ($feeBearType == CarrierPayChannel::FEE_BEAR_PLAYER) {
                            $this->log->finally_amount = bcsub($this->log->finally_amount, $depositFee, 2);
                            $this->log->is_fee_amt = 1;
                            $playerDespositFee = $depositFee;
                        }
                        // 计算存款优惠承担,更新银行卡余额, 如果是佣金代理, 那么佣金代理承担存款优惠;
                        if ($depositBenefit > 0) {
                            if ($this->log->player->agent->isCommissionAgent()) {
                                $agentUnderTakeLog = new AgentBearUndertakenLog();
                                // 获取最大承担比例;
                                $conf = $this->log->player->agent->agentLevel->commissionAgentConf;
                                // 计算优惠承担比例
                                $deposit_preferential_undertake_ratio = bcdiv(
                                    $conf->deposit_preferential_undertake_ratio, 100, 5);
                                $agentUnderTakeBenefitAmount = bcmul($deposit_preferential_undertake_ratio,
                                    $depositBenefit, 2);
                                $carrierUnderTakeBenefitAmount = 0.00;
                                $remark = '';
                                if (bccomp($conf->deposit_preferential_undertake_max, 0.00, 2) > 0 && bccomp(
                                    $agentUnderTakeBenefitAmount, $conf->deposit_preferential_undertake_max, 2) > 0) {
                                    $agentUnderTakeBenefitAmount = $conf->deposit_preferential_undertake_max;
                                }
                                
                                // 计算运营商承担数据
                                $carrierUnderTakeBenefitAmount = bcsub($depositBenefit, $agentUnderTakeBenefitAmount, 2);
                                
                                $agentUnderTakeLog->amount = $agentUnderTakeBenefitAmount;
                                $agentUnderTakeLog->company_amount = $carrierUnderTakeBenefitAmount;
                                $agentUnderTakeLog->agent_id = $this->log->player->agent->id;
                                $agentUnderTakeLog->carrier_id = $this->log->player->carrier_id;
                                $agentUnderTakeLog->undertaken_type = AgentBearUndertakenLog::UNDERTAKEN_TYPE_DEPOSIT_BENEFIT;
                                bccomp($agentUnderTakeLog->amount, 0.00, 2) > 0 && $agentUnderTakeLog->save();
                            }
                            // 获得优惠后的金额
                            $playerAmtAfterBenefit = bcadd($playerOldAmt, $depositBenefit);
                            // 用户获得优惠后日志
                            $playerAccountLog = new PlayerAccountLog();
                            $playerAccountLog->amount = $depositBenefit;
                            $playerAccountLog->carrier_id = $this->log->player->carrier_id;
                            $playerAccountLog->player_id = $this->log->player->player_id;
                            $playerAccountLog->fund_type = PlayerAccountLog::FUND_TYPE_DEPOSIT_BENEFIT;
                            $playerAccountLog->fund_source = '用户存款优惠';
                            $playerAccountLog->remark = '会员存款优惠：' . $depositBenefit . ',主账户原余额：' . $playerOldAmt .
                                 '，主账户现余额：' . $playerAmtAfterBenefit;
                            $playerAccountLog->save();
                        }
                        $balance = bcsub($this->log->amount, $companyDepositFee, 2); // 实际入账金额
                        $carrierPayChannel->balance = bcadd($carrierPayChannel->balance, $balance, 2); // 公司支付渠道增加金额
                        $this->log->player->main_account_amount = bcadd($this->log->player->main_account_amount,
                            $this->log->finally_amount, 2);
                        // 会员存款记录日志
                        $playerAccountLog = new PlayerAccountLog();
                        $playerAccountLog->amount = $this->log->amount;
                        $playerAccountLog->carrier_id = $this->log->player->carrier_id;
                        $playerAccountLog->player_id = $this->log->player->player_id;
                        $playerAccountLog->fund_type = PlayerAccountLog::FUND_TYPE_DEPOSIT;
                        $payChannelType = $carrierPayChannel->payChannel->payChannelType; // ->isCompanyPay();
                        if ($payChannelType->isCompanyPay()) {
                            $playerAccountLog->fund_source = '公司入款';
                        } else if ($payChannelType->isThirdPartPay()) {
                            $playerAccountLog->fund_source = '在线支付';
                        } else {
                            $playerAccountLog->fund_source = '点卡充值';
                        }
                        $playerAccountLog->remark = '会员存款：' . $this->log->amount . '手续费：' . $playerDespositFee . ',实际到账：' .
                             bcsub($this->log->amount, $playerDespositFee) . ',主账户原余额：' . $playerAmtAfterBenefit .
                             '，主账户现余额：' . $this->log->player->main_account_amount;
                        $playerAccountLog->save();
                        // 运营商资金流水
                        $companyDepositLog = new CarrierQuotaConsumptionLog();
                        $companyDepositLog->amount = $balance;
                        $companyDepositLog->related_pay_channel = $this->log->carrier_pay_channel;
                        $companyDepositLog->pay_channel_remain_amount = $carrierPayChannel->balance;
                        $companyDepositLog->consumption_source = '会员【' . $this->log->player->user_name . '】存款：' .
                             $this->log->amount;
                        if (bccomp($companyDepositFee, 0, 2) > 0) {
                            $companyDepositLog->consumption_source .= ',手续费：' . $companyDepositFee;
                        }
                        $companyDepositLog->consumption_source .= ',实际入账：' . $balance;
                        $companyDepositLog->carrier_id = $this->log->carrier_id;
                        $companyDepositLog->amount > 0 && $companyDepositLog->save();
                        
                        // 取款流水限制
                        $withdrawFlowLimit = new PlayerWithdrawFlowLimitLog();
                        $withdrawFlowLimit->carrier_id = $this->log->carrier_id;
                        if ($this->log->carrier_activity_id) {
                            $withdrawFlowLimit->related_activity = $this->log->carrier_activity_id;
                            // 主动参与的优惠活动
                            dispatch(new JudgePlayerHasNotAutoJoinActivity($this->log->player, $this->log->ip, $this->log->carrier_activity_id));
                        }
                        $withdrawFlowLimit->limit_amount = $this->log->finally_amount;
                        $withdrawFlowLimit->limit_type = PlayerWithdrawFlowLimitLog::LIMIT_TYPE_PLAYER_DEPOSIT;
                        $withdrawFlowLimit->player_account_log = $playerAccountLog->log_id;
                        $withdrawFlowLimit->player_id = $this->log->player->player_id;
                        $withdrawFlowLimit->limit_amount > 0 && $withdrawFlowLimit->save();
                        $this->log->player->update();
                        $carrierPayChannel->update();
                        $this->log->update();
                    });
                // 查找是否有自动参与的优惠活动
                dispatch(new JudgePlayerHasAutoJoinActivity($this->log->player, $this->log->ip));
                // 玩家升级队列处理
                dispatch(
                    new PlayerUpgradeLevelHandle(PlayerInfoCacheHelper::getPlayerCacheInfoById($this->log->player_id)));
                /*
                 * 审核活动 队列执行
                 * if ($this->log->carrier_activity_id) {
                 * $activity = CarrierActivity::findOrFail($this->log->carrier_activity_id);
                 * $carrierActivityAudit = new CarrierActivityAudit();
                 * $carrierActivityAudit->act_id = $activity->id;
                 * $carrierActivityAudit->carrier_id = $this->log->carrier_id;
                 * $carrierActivityAudit->player_id = $this->log->player_id;
                 * $carrierActivityAudit->ip = $this->log->ip;
                 * try {
                 * $activity->checkUserCanApplyActivity($this->log->player_id, $this->log->ip);
                 * $carrierActivityAudit->status = CarrierActivityAudit::STATUS_AUDIT;
                 * } catch (CarrierRuntimeException $e) {
                 * $carrierActivityAudit->remark = $e->getMessage();
                 * $carrierActivityAudit->status = CarrierActivityAudit::STATUS_REFUSE;
                 * } catch (\Exception $e) {
                 * \Log::info([
                 * '用户存款参与活动出错' => $e->getMessage()
                 * ]);
                 * throw $e;
                 * }
                 * $carrierActivityAudit->save();
                 * }
                 */
            } catch (\PDOException $e) {
                \Log::info([
                    '用户存款出错' => $e->getMessage()
                ]);
            }
        }
    }
}

