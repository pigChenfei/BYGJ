<?php

namespace App\Jobs;

use App\Helpers\Caches\CarrierInfoCacheHelper;
use App\Models\Conf\CarrierInvitePlayerConf;
use App\Models\Log\CarrierQuotaConsumptionLog;
use App\Models\Log\PlayerAccountLog;
use App\Models\Log\PlayerBetFlowLog;
use App\Models\Log\PlayerDepositPayLog;
use App\Models\Log\PlayerInviteRewardLog;
use App\Models\Log\PlayerWithdrawFlowLimitLog;
use App\Models\Player;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PlayerInviteDepositRewardHandle implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @var Player
     */
    private $player;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $invitePlayerConf = $this->getCachedInvitePlayerConf();

        //存款奖励
       $this->handleDepositReward($invitePlayerConf);
    }

    private function handleBetReward(CarrierInvitePlayerConf $conf)
    {
        $betRewardRule = null;
        if ($conf->bet_reward_rule) {
            $betRewardRule = json_decode($conf->bet_reward_rule, true);
            if (!$betRewardRule) {
                \WLog::info('没有投注奖励设置');
                return;
            };
        }
        $commendedPlayer = Player::find($this->player->recommend_player_id);
        if(is_null($commendedPlayer)) {
            return;
        }
        //获取会员最近的一次结算记录;
        $log = PlayerInviteRewardLog::byPlayerId($commendedPlayer->player_id)->byRewardType(PlayerInviteRewardLog::SETTLE_TYPE_BET)->latestLog()->first();
        $lastSettleTime = null;
        if (!$log) {
            //如果没有结算记录,那么将玩家的创建日期定为上次结算日期
            $lastSettleTime = $commendedPlayer->created_at;
        } else {
            $lastSettleTime = $log->created_at;
        }
        //判断当前时间是否满足结算周期;
        $settle_period = $conf->bet_reward_settle_period;
        $time = Carbon::now();
        //如果上次的结算日期距离现在超过结算周期, 那么计算奖励数据;
        if ($lastSettleTime->timestamp + $settle_period * 86400 <= $time->timestamp) {
            //获取从上次结算日期到现在的有效投注额
            $availableBetAmount = PlayerBetFlowLog::betFlowAvailable()->byPlayerId($this->player->player_id)->byFinishTimeRange($lastSettleTime->toDateTimeString(), $time->toDateTimeString())->sum('available_bet_amount');
            $totalBetAmount = PlayerBetFlowLog::byPlayerId($this->player->player_id)->byFinishTimeRange($lastSettleTime->toDateTimeString(), $time->toDateTimeString())->sum('bet_amount');
            if ($availableBetAmount > 0) {
                \WLog::info('有效投注额奖励开始处理, 有效投注额:'.$availableBetAmount.' 总投注额:'.$totalBetAmount);
                //如果有效投注额大于0; 则可以结算, 开始计算存款数据, 因为存款数据也需要存入数据库;
                $depositAmount = PlayerDepositPayLog::payedSuccessfully()->byPlayerId($this->player->player_id)->byFinishTimeRange($lastSettleTime->toDateTimeString(), $time->toDateTimeString())->sum('amount');
                //计算最终投注奖励金额
                $rewardAmount = 0;
                $invitedPlayerRewardAmount = 0;
                foreach ($betRewardRule as $item) {
                    if ($availableBetAmount >= $item['availableBetAmount']) {
                        $rewardAmount = $availableBetAmount * $item['playerRewardPercent'] * 0.01;
                        $rewardAmount = $rewardAmount >= $item['playerRewardMax'] ? $item['playerRewardMax'] : $rewardAmount;
                        $invitedPlayerRewardAmount = $availableBetAmount * $item['invitePlayerRewardPercent'] * 0.01;
                        $invitedPlayerRewardAmount = $invitedPlayerRewardAmount >= $item['invitePlayerRewardMax'] ? $item['playerRewardMax'] : $invitedPlayerRewardAmount;
                    }
                }
                try {
                    \DB::transaction(function () use ($invitedPlayerRewardAmount, $depositAmount, $availableBetAmount, $totalBetAmount, $rewardAmount, $commendedPlayer) {
                        if($rewardAmount > 0){
                            $this->rewardPlayer($commendedPlayer, $this->player->player_id, $depositAmount, $availableBetAmount, $totalBetAmount, PlayerInviteRewardLog::SETTLE_TYPE_BET, $rewardAmount);
                            $this->recordPlayerAccountLog($commendedPlayer, $rewardAmount, PlayerInviteRewardLog::SETTLE_TYPE_BET);
                        }
                        if ($invitedPlayerRewardAmount > 0) {
                            $this->rewardPlayer($this->player, null, $depositAmount, $availableBetAmount, $totalBetAmount, PlayerInviteRewardLog::SETTLE_TYPE_SELF_BET, $invitedPlayerRewardAmount);
                            $this->recordPlayerAccountLog($this->player, $invitedPlayerRewardAmount, PlayerInviteRewardLog::SETTLE_TYPE_SELF_BET);
                        }
                    });
                    \WLog::info('玩家投注额奖励处理成功 玩家:' . $commendedPlayer->user_name . ' 奖励金额:' . $rewardAmount . ' 邀请好友:' . $this->player->user_name . ' 奖励金额:' . $invitedPlayerRewardAmount);
                } catch (\Exception $e) {
                    \WLog::error('玩家投注额奖励处理失败: 玩家:' . $commendedPlayer->user_name . ' 邀请好友:' . $this->player->user_name . ' 错误:' . $e->getMessage());
                    throw $e;
                }
            }
        }else{
            \WLog::info('处理投注奖励周期未到, 跳过处理');
        }
    }

    private function handleDepositReward(CarrierInvitePlayerConf $conf)
    {
        $depositRewardRule = null;
        if ($conf->bet_reward_rule) {
            $depositRewardRule = json_decode($conf->deposit_reward_rule, true);
            if (!$depositRewardRule) {
                return;
            };
        }
        $commendedPlayer = Player::findOrFail($this->player->recommend_player_id);
        //获取会员最近的一次结算记录;
        $log = PlayerInviteRewardLog::byPlayerId($commendedPlayer->player_id)->byRewardType(PlayerInviteRewardLog::SETTLE_TYPE_DEPOSIT)->latestLog()->first();
        $lastSettleTime = null;
        if (!$log) {
            //如果没有结算记录,那么将玩家的创建日期定为上次结算日期
            $lastSettleTime = $commendedPlayer->created_at;
        } else {
            $lastSettleTime = $log->created_at;
        }
        //判断当前时间是否满足结算周期;
        $settle_period = $conf->deposit_reward_settle_period;
        $time = Carbon::now();
        //如果上次的结算日期距离现在超过结算周期, 那么计算奖励数据;
        if ($lastSettleTime->timestamp + $settle_period * 86400 <= $time->timestamp) {
            //获取从上次结算日期到现在的有效投注额
            $availableBetAmount = PlayerBetFlowLog::betFlowAvailable()->byPlayerId($this->player->player_id)->byFinishTimeRange($lastSettleTime->toDateTimeString(), $time->toDateTimeString())->sum('available_bet_amount');
            $totalBetAmount = PlayerBetFlowLog::byPlayerId($this->player->player_id)->byFinishTimeRange($lastSettleTime->toDateTimeString(), $time->toDateTimeString())->sum('bet_amount');
            //如果有效投注额大于0; 则可以结算
            //\DB::connection()->enableQueryLog();
            $depositAmount = PlayerDepositPayLog::payedSuccessfully()->byPlayerId($this->player->player_id)->ByFinishUpdateTimeRange($lastSettleTime->toDateTimeString(), $time->toDateTimeString())->sum('amount');
            //$queries = \DB::getQueryLog(); // 获取查询日志
            //\WLog::info(json_encode($queries));

            \WLog::info('用户ID'.$this->player->player_id.'有效投注额'.$depositAmount.'时间1'.$lastSettleTime->toDateTimeString().'时间2'.$time->toDateTimeString());
            if ($depositAmount > 0) {
                \WLog::info('有存款额奖励开始处理, 有效投注额:'.$availableBetAmount.' 总投注额:'.$totalBetAmount.' 区间存款额:'.$depositAmount);
                $rewardAmount = 0;
                $invitedPlayerRewardAmount = 0;
                foreach ($depositRewardRule as $item) {
                    if ($depositAmount >= $item['depositAmount']) {
                        $rewardAmount = $item['playerRewardAmount'];
                        $invitedPlayerRewardAmount = $item['invitePlayerRewardAmount'];
                    }
                }
                $commendedPlayer = Player::findOrFail($this->player->recommend_player_id);
                try {
                    \DB::transaction(function () use ($invitedPlayerRewardAmount, $depositAmount, $availableBetAmount, $totalBetAmount, $rewardAmount, $commendedPlayer) {
                        if($rewardAmount > 0){
                            $this->rewardPlayer($commendedPlayer, $this->player->player_id, $depositAmount, $availableBetAmount, $totalBetAmount, PlayerInviteRewardLog::SETTLE_TYPE_DEPOSIT, $rewardAmount);
                            $this->recordPlayerAccountLog($commendedPlayer, $rewardAmount, PlayerInviteRewardLog::SETTLE_TYPE_DEPOSIT);
                        }
                        if ($invitedPlayerRewardAmount > 0) {
                            $this->rewardPlayer($this->player, null, $depositAmount, $availableBetAmount, $totalBetAmount, PlayerInviteRewardLog::SETTLE_TYPE_SELF_DEPOSIT, $invitedPlayerRewardAmount);
                            $this->recordPlayerAccountLog($this->player, $invitedPlayerRewardAmount, PlayerInviteRewardLog::SETTLE_TYPE_SELF_DEPOSIT);
                        }
                    });
                    \WLog::info('玩家存款额奖励处理成功 玩家:' . $commendedPlayer->user_name . ' 奖励金额:' . $rewardAmount . ' 邀请好友:' . $this->player->user_name . ' 奖励金额:' . $invitedPlayerRewardAmount);
                } catch (\Exception $e) {
                    \WLog::error('玩家存款额奖励处理失败: 玩家:' . $commendedPlayer->user_name . ' 邀请好友:' . $this->player->user_name . ' 错误:' . $e->getMessage());
                    throw $e;
                }
            }else{
                \WLog::info('存款额为0, 跳过处理');
            }
        }else{
            \WLog::info('处理存款奖励周期未到, 跳过处理');
        }
    }

    /**
     * 奖励邀请人及玩家
     * @param $playerId
     * @param $relatedPlayerId
     * @param $depositAmount
     * @param $availableBetAmount
     * @param $totalBetAmount
     * @param $type
     * @param $rewardAmount
     */
    private function rewardPlayer(Player $player, $relatedPlayerId, $depositAmount, $availableBetAmount, $totalBetAmount, $type, $rewardAmount)
    {
        $log = new PlayerInviteRewardLog();
        $log->player_id = $player->player_id;
        $log->carrier_id  = $player->carrier_id;
        $log->reward_related_player = $relatedPlayerId;
        $log->related_player_deposit_amount = $depositAmount;
        $log->related_player_bet_amount = $totalBetAmount;
        $log->related_player_validate_bet_amount = $availableBetAmount;
        $log->reward_amount = $rewardAmount;
        $log->reward_type = $type;
        $log->save();
    }


    /**
     *记录玩家账户记录
     */
    private function recordPlayerAccountLog(Player $player, $amount, $type)
    {
//        if($player->carrier->isRemainQuotaEnough($amount)){
//            throw new \Exception('运营商额度不足');
//        }
        //玩家账户记录
        $log = new PlayerAccountLog();
        $log->player_id = $player->player_id;
        $log->carrier_id = $player->carrier_id;
        $log->amount = $amount;
        $log->fund_type = PlayerAccountLog::FUND_TYPE_INVITED_PLAYER_REWARD;
        $log->remark = '系统自动结算';
        $log->save();

        //增加流水限制
        $withdrawFlowLimit = new PlayerWithdrawFlowLimitLog();
        $withdrawFlowLimit->carrier_id = $player->carrier_id;
        $withdrawFlowLimit->player_id  = $player->player_id;
        $withdrawFlowLimit->limit_amount = $amount;
        $withdrawFlowLimit->operator_id = \WinwinAuth::carrierUser() ? \WinwinAuth::carrierUser()->id : null;
        $withdrawFlowLimit->player_account_log = $log->log_id;
        $withdrawFlowLimit->limit_type = PlayerWithdrawFlowLimitLog::LIMIT_TYPE_PLAYER_INVITE_REWARD;
        $withdrawFlowLimit->limit_amount > 0 && $withdrawFlowLimit->save();

        //运营商额度消费记录
        $consumptionLog = new CarrierQuotaConsumptionLog();
        $consumptionLog->carrier_id = $player->carrier_id;
        switch ($type) {
            case PlayerInviteRewardLog::SETTLE_TYPE_BET:
                $log->fund_source = '会员邀请好友投注额奖励结算';
                $consumptionLog->consumption_source = '会员邀请好友投注额奖励结算';
                break;
            case PlayerInviteRewardLog::SETTLE_TYPE_DEPOSIT:
                $log->fund_source = '会员邀请好友存款额奖励结算';
                $consumptionLog->consumption_source = '会员邀请好友存款额奖励结算';
                break;
            case PlayerInviteRewardLog::SETTLE_TYPE_SELF_BET:
                $log->fund_source = '会员投注额奖励结算';
                $consumptionLog->consumption_source = '会员投注额奖励结算';
                break;
            case PlayerInviteRewardLog::SETTLE_TYPE_SELF_DEPOSIT:
                $log->fund_source = '会员存款额奖励结算';
                $consumptionLog->consumption_source = '会员存款额奖励结算';
                break;
            default:
                return;
        }

        $consumptionLog->amount = -$amount;
        $consumptionLog->remark = '系统自动结算';
        $consumptionLog->save();
    }

    /**
     * @return CarrierInvitePlayerConf
     */
    private function getCachedInvitePlayerConf()
    {
        return CarrierInfoCacheHelper::getCachedInvitePlayerConf($this->player->carrier);
    }

}
