<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 2017/4/25
 * Time: 下午5:48
 */

namespace App\Models\Activity\ActivityPassReviewBonusFactory;
use App\Models\Activity\CarrierActivityAmphotericGamePlat;
use App\Models\Log\PlayerBetFlowLog;
use App\Models\Player;
use Carbon\Carbon;


/**
 * 昨日正负盈利百分比
 * Class ActivityPassReviewBonusYesterdayWinLosePercentage
 * @package App\Models\Activity\ActivityPassReviewBonusFactory
 */
class ActivityPassReviewBonusYesterdayWinLosePercentage extends ActivityPassReviewBonusAbstract
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
        //获取正负盈利的游戏平台
        $gamePlats = $this->carrierActivity->amphotericWinLoseGamePlats->map(function (CarrierActivityAmphotericGamePlat $plat){ return $plat->carrier_game_plat_id; })->toArray();
        $builder = PlayerBetFlowLog::byFinishTimeRange(Carbon::yesterday()->startOfDay()->toDateTimeString(),Carbon::yesterday()->endOfDay()->toDateTimeString())->byPlayerId($player->player_id);
        if($gamePlats){
            $builder->inGamePlats($gamePlats);
        }
        $bonus = 0;
        //昨日盈利
        $companyWinLose = $builder->sum('company_win_amount');
        $depositLog = $this->getRecentlyDepositLog($player);
        if($ruleArray && $companyWinLose && $depositLog){
            $flowLimitTimes = 0;
            $companyIsWinner = $companyWinLose > 0;
            $companyWinLose = abs($companyWinLose);
            foreach ($ruleArray as $rule){
                if($companyIsWinner){
                    if($companyWinLose >= $rule['fixedAmount'] && $rule['amphoteric'] == 1){
                        $bonus = $depositLog->amount * $rule['bonusesAatio'] * 0.01;
                        $flowLimitTimes = $rule['flowMultiple'];
                    }
                }else{
                    if($companyWinLose >= $rule['fixedAmount'] && $rule['amphoteric'] == -1){
                        $bonus = $depositLog->amount * $rule['bonusesAatio'] * 0.01;
                        $flowLimitTimes = $rule['flowMultiple'];
                    }
                }
            }
            $withdrawLimitAmount = $this->getWithdrawLimitAmount($flowLimitTimes,$depositLog ? $depositLog->amount : 0 ,$bonus);
            return [$bonus,$withdrawLimitAmount];
        }
        return [0,0];
    }
}