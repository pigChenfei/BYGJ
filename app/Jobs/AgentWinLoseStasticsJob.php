<?php
namespace App\Jobs;

use App\Helpers\Caches\CarrierInfoCacheHelper;
use App\Models\Carrier;
use App\Models\Log\GameWinLoseStastics;
use App\Models\Log\PlayerAccountLog;
use App\Models\Log\PlayerBetFlowLog;
use App\Models\Log\PlayerRebateFinancialFlow;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Log\AgentWinLoseStastics;
use App\Models\Player;
use App\Models\Log\PlayerLoginLog;
use App\Models\Log\PlayerDepositPayLog;
use App\Models\Log\PlayerWithdrawLog;

class AgentWinLoseStasticsJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     *
     * @var Carrier
     */
    private $carrier;

    private $agentId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($carrier_id, $agentId)
    {
        $this->carrier = CarrierInfoCacheHelper::getCachedCarrierInfoByCarrierId($carrier_id);
        $this->agentId = $agentId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 当前统计10分钟之前的数据;
        $now = Carbon::now();
        $endTime = $now->subMinutes(10);
        // 如果当前时间和十分钟之前不是同一天, 那么结束时间算为昨天12点
        if ($endTime->isSameDay($now) == false) {
            $endTime = $now->subDay()->endOfDay();
        }
        $startTimeString = $endTime->copy()
            ->startOfDay()
            ->toDateTimeString();
        $endTimeString = $endTime->copy()->toDateTimeString();
        try {
            $log = AgentWinLoseStastics::whereBetween('created_at', [
                $startTimeString,
                $endTimeString
            ])->where('carrier_id', $this->carrier->id)
                ->where('agent_id', $this->agentId)
                ->first();
            if (! $log) {
                $log = new AgentWinLoseStastics();
                $log->carrier_id = $this->carrier->id;
                $log->agent_id = $this->agentId;
                $log->created_at = $now->startOfDay();
            }
            
            // 注册数
            $registerCount = Player::whereBetween('created_at', [
                $startTimeString,
                $endTimeString
            ])->where('carrier_id', $this->carrier->id)
                ->where('agent_id', $this->agentId)
                ->count();
            $log->register_count = $registerCount;
            // 登录数
            $loginCount = PlayerLoginLog::whereBetween('created_at', [
                $startTimeString,
                $endTimeString
            ])->whereHas('player', function ($query) {
                $query->where('agent_id', $this->agentId);
            })
                ->where('carrier_id', $this->carrier->id)
                ->count();
            $log->login_count = $loginCount;
            // 存款额
            $depositAmount = PlayerDepositPayLog::payedSuccessfully()->whereBetween('created_at', [
                $startTimeString,
                $endTimeString
            ])
                ->whereHas('player', function ($query) {
                $query->where('agent_id', $this->agentId);
            })
                ->where('carrier_id', $this->carrier->id)
                ->sum('amount');
            $log->deposit_amount = $depositAmount;
            
            // 红利额
//            $bonus = PlayerDepositPayLog::payedSuccessfully()->whereBetween('created_at', [
//                $startTimeString,
//                $endTimeString
//            ])
//                ->whereHas('player', function ($query) {
//                $query->where('agent_id', $this->agentId);
//            })
//                ->where('carrier_id', $this->carrier->id)
//                ->sum('bonus_amount');
            $bonus = PlayerAccountLog::whereBetween('created_at',[$startTimeString,$endTimeString])
                ->where('fund_type',PlayerAccountLog::FUND_TYPE_BONUS)
                ->where('carrier_id',$this->carrier->id)
                ->whereHas('player', function ($query) {
                    $query->where('agent_id', $this->agentId);
                })
                ->sum('amount');
            $log->bonus_amount = $bonus;
            // 存款优惠
            $depositBenefitAmount = PlayerDepositPayLog::payedSuccessfully()->whereBetween('created_at', [
                $startTimeString,
                $endTimeString
            ])
                ->whereHas('player', function ($query) {
                $query->where('agent_id', $this->agentId);
            })
                ->where('carrier_id', $this->carrier->id)
                ->sum('benefit_amount');
            $log->deposit_benefit_amount = $depositBenefitAmount;
            // 洗码
            $log->rebate_financial_flow_amount = PlayerRebateFinancialFlow::whereBetween('created_at', [
                $startTimeString,
                $endTimeString
            ])->whereHas('player', function ($query) {
                $query->where('agent_id', $this->agentId);
            })
                ->where('carrier_id', $this->carrier->id)
                ->sum('rebate_financial_flow_amount');
            
            // 首存额
            $sql = "SELECT A.amount FROM log_player_deposit_pay A join inf_player as p on A.player_id = p.player_id WHERE NOT EXISTS(
        SELECT 1 FROM log_player_deposit_pay B WHERE B.player_id = A.player_id AND B.`status` = 1 
        AND B.created_at < A.created_at AND B.carrier_id = {$this->carrier->id} ) AND A.created_at <= '{$endTimeString}' 
        AND A.created_at >= '{$startTimeString}' AND A.carrier_id = {$this->carrier->id} AND A.`status` = 1 AND p.agent_id = {$this->agentId}";
            $data = \DB::select($sql);
            if ($data) {
                $log->first_deposit_count = 0;
                $log->first_deposit_amount = array_reduce(array_map(function ($element) {
                    return $element->amount;
                }, $data), function ($pre, $next) use ($log) {
                    $log->first_deposit_count += 1;
                    return $pre + $next;
                }, 0);
            }
            
            // 取款额
            $log->withdraw_amount = PlayerWithdrawLog::accountOut()->whereBetween('created_at', [
                $startTimeString,
                $endTimeString
            ])
                ->whereHas('player', function ($query) {
                $query->where('agent_id', $this->agentId);
            })
                ->where('carrier_id', $this->carrier->id)
                ->sum('finally_withdraw_amount');
            // 公司输赢
            $log->winlose_amount = PlayerBetFlowLog::betFlowAvailable()->gameFinished()
                ->whereBetween('created_at', [
                $startTimeString,
                $endTimeString
            ])->whereHas('player', function ($query) {
                    $query->where('agent_id', $this->agentId);
                })
                ->where('carrier_id', $this->carrier->id)
                ->sum('company_win_amount');
            
            $log->carrier_income = $log->winlose_amount - $log->bonus_amount - $log->rebate_financial_flow_amount - $log->deposit_benefit_amount;
            
            $log->save();
        } catch (\Exception $e) {
            \WLog::error('代理输赢统计异常====>', [
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ]);
        }
        \WLog::info('代理输赢统计时间区间: ' . $startTimeString . '--' . $endTimeString);
    }
}
