<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 2017/4/25
 * Time: 下午5:49
 */

namespace App\Models\Activity\ActivityPassReviewBonusFactory;
use App\Models\Player;


/**
 * 会员等级存送百分比
 * Class ActivityPassReviewBonusPlayerLevelDepositPercentage
 * @package App\Models\Activity\ActivityPassReviewBonusFactory
 */
class ActivityPassReviewBonusPlayerLevelDepositPercentage extends ActivityPassReviewBonusAbstract
{

    public function handleBonus(Player $player)
    {
        $bonus = 0;
        //获取最近存款成功的存款记录;
        $depositLog = $this->getRecentlyDepositLog($player);
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
        //获取当前玩家会员等级
        $playerLevel = $player->player_level_id;
        $bonus = 0;
        //获取最近存款成功的存款记录;
        $depositLog = $this->getRecentlyDepositLog($player);
        if($depositLog && $depositLog->amount > 0 && $playerLevel && $ruleArray){
            $flowLimitTimes = 0;
            foreach ($ruleArray as $item){
                if($item['memberLevel'] == $playerLevel && $depositLog->amount >= $item['fixedAmount']){
                    $bonus = $depositLog->amount * $item['bonusesAatio'] * 0.01;
                    $bonus = $bonus >= $item['bonusesMax'] ? $item['bonusesMax'] : $bonus;
                    $flowLimitTimes = $item['flowMultiple'];
                }
            }
            $withdrawLimitAmount = $this->getWithdrawLimitAmount($flowLimitTimes,$depositLog->amount, $bonus);
            return [$bonus,$withdrawLimitAmount];
        }
        return [0,0];
    }
}