<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 2017/4/25
 * Time: 下午5:47
 */

namespace App\Models\Activity\ActivityPassReviewBonusFactory;
use App\Models\CarrierActivity;
use App\Models\Log\PlayerDepositPayLog;
use App\Models\Player;


/**
 * 存送固定红利
 * Class ActivityPassReviewBonusDepositFixedBonus
 * @package App\Models\Activity\ActivityPassReviewBonusFactory
 */
class ActivityPassReviewBonusDepositFixedBonus extends ActivityPassReviewBonusAbstract
{

    public function handleBonus(Player $player)
    {
        //获取最近一次没有申请活动,并且存款成功的存款记录;
        $depositLog = $this->getRecentlyDepositLog($player);
        //如果有存款, 处理最近的存款数据
        $bonus = 0;
        if($depositLog){
            list($bonus,$withdrawLimitAmount) = $this->getBonusAndWithdrawLimitFlow($player);
            if($bonus > 0){
                $this->modifyDepositLogBonus($player,$bonus);
                $this->newBonusRecord($player,$bonus);
                if($withdrawLimitAmount > 0){
                    $limitGamePlats = $this->carrierActivity->activityWithdrawFlowLimitGamePlats;
                    $this->newWithdrawLimitLog($player,$withdrawLimitAmount,$limitGamePlats);
                }
            }
        }
        $this->updateCarrierActivityJoinTimes($player,$bonus);
    }

    public function getBonusAndWithdrawLimitFlow(Player $player)
    {
        $ruleArray = json_decode($this->carrierActivity->rebate_financial_bonuses_step_rate_json,true);
        //获取最近一次没有申请活动,并且存款成功的存款记录;
        $depositLog = $this->getRecentlyDepositLog($player);
        $bonus = 0;
        if($depositLog && $ruleArray){
            $amount = $depositLog->amount;
            $flowLimitTimes = 0;
            foreach ($ruleArray as $rule){
                if($amount >= $rule['fixedAmount']){
                    $bonus = $rule['bonusesAatio'];
                    $flowLimitTimes = $rule['flowMultiple'];
                }
            }
            $withdrawLimitAmount = $this->getWithdrawLimitAmount($flowLimitTimes,$amount,$bonus);
            return [$bonus,$withdrawLimitAmount];
        }
        return [0,0];
    }


}