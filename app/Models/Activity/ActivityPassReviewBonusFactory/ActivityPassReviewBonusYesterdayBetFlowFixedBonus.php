<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 2017/4/25
 * Time: 下午5:48
 */

namespace App\Models\Activity\ActivityPassReviewBonusFactory;
use App\Models\Log\PlayerBetFlowLog;
use App\Models\Player;
use Carbon\Carbon;


/**
 * 昨日投注额固定红利
 * Class ActivityPassReviewBonusYesterdayBetFlowFixedBonus
 * @package App\Models\Activity\ActivityPassReviewBonusFactory
 */
class ActivityPassReviewBonusYesterdayBetFlowFixedBonus extends ActivityPassReviewBonusAbstract
{


    public function handleBonus(Player $player)
    {
        list($bonus,$withdrawLimitAmount) = $this->getBonusAndWithdrawLimitFlow($player);
        if($bonus > 0){
            $this->newBonusRecord($player,$bonus);
            if($withdrawLimitAmount > 0){
                $limitGamePlats = $this->carrierActivity->activityWithdrawFlowLimitGamePlats;
                $this->newWithdrawLimitLog($player,$withdrawLimitAmount,$limitGamePlats);
            }
        }
        $this->updateCarrierActivityJoinTimes($player,$bonus);
    }

    public function getBonusAndWithdrawLimitFlow(Player $player)
    {
        $ruleArray = json_decode($this->carrierActivity->rebate_financial_bonuses_step_rate_json,true);
        //获取最近一次没有申请活动,并且存款成功的存款记录;
        $depositLog = $this->getRecentlyDepositLog($player);
        //获取昨日投注额;
        $availableBetFlow = PlayerBetFlowLog::byFinishTimeRange(Carbon::yesterday()->startOfDay()->toDateTimeString(),Carbon::yesterday()->endOfDay()->toDateTimeString())->byPlayerId($player->player_id)->sum('available_bet_amount');
        $bonus = 0;
        if($availableBetFlow && $ruleArray){
            $flowLimitTimes = 0;
            foreach ($ruleArray as $item){
                if($availableBetFlow >= $item['fixedAmount']){
                    $bonus = $item['bonusesAatio'];
                    $flowLimitTimes = $item['flowMultiple'];
                }
            }
            $withdrawLimitAmount = $this->getWithdrawLimitAmount($flowLimitTimes,$depositLog ? $depositLog->amount : 0 ,$bonus);
            return [$bonus,$withdrawLimitAmount];
        }
        return [0,0];
    }

}