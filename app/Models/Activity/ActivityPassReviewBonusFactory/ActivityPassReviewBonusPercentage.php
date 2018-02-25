<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 2017/4/25
 * Time: 下午5:45
 */
namespace App\Models\Activity\ActivityPassReviewBonusFactory;

use App\Models\Player;

/**
 * 存送百分比
 * Class ActivityPassReviewBonusPercentage
 *
 * @package App\Models\Activity\ActivityPassReviewBonusFactory
 */
class ActivityPassReviewBonusPercentage extends ActivityPassReviewBonusAbstract
{

    public function handleBonus(Player $player)
    {
        
        // 获取最近一次存款成功的存款记录;
        $depositLog = $this->getRecentlyDepositLog($player);
        // 如果有存款, 处理最近的存款数据
        $bonus = 0;
        if ($depositLog) {
            list ($bonus, $withdrawLimitAmount) = $this->getBonusAndWithdrawLimitFlow($player);
            if ($bonus > 0) {
                $this->modifyDepositLogBonus($player, $bonus);
                $this->newBonusRecord($player, $bonus);
                if ($withdrawLimitAmount > 0) {
                    $limitGamePlats = $this->carrierActivity->activityWithdrawFlowLimitGamePlats;
                    $this->newWithdrawLimitLog($player, $withdrawLimitAmount, $limitGamePlats);
                }
            }
        }
        $this->updateCarrierActivityJoinTimes($player, $bonus);
    }

    public function getBonusAndWithdrawLimitFlow(Player $player)
    {
        $ruleArray = json_decode($this->carrierActivity->rebate_financial_bonuses_step_rate_json, true);
        // 获取最近一次存款成功的存款记录;
        $depositLog = $this->getRecentlyDepositLog($player);
        $bonus = 0;
        // 如果有存款, 处理最近的存款数据
        if ($depositLog && $ruleArray) {
            $amount = $depositLog->amount;
            $flowLimitTimes = 0;
            foreach ($ruleArray as $rule) {
                if (bccomp($amount, $rule['fixedAmount'], 2) >= 0) {
                    $bonus = bcmul($amount, bcdiv($rule['bonusesAatio'], 100, 5), 2);
                    if (bccomp($bonus, $rule['bonusesMax'], 2) > 0) {
                        $bonus = $rule['bonusesMax'];
                    }
                    $flowLimitTimes = $rule['flowMultiple'];
                    \WLog::info('红利金额计算',
                        [
                            'max' => $rule['bonusesMax'],
                            'current' => $bonus,
                            'flowLimitTimes' => $flowLimitTimes
                        ]);
                    break;
                }
            }
            $withdrawLimitAmount = $this->getWithdrawLimitAmount($flowLimitTimes, $amount, $bonus);
            return [
                $bonus,
                $withdrawLimitAmount
            ];
        }
        return [
            0,
            0
        ];
    }
}