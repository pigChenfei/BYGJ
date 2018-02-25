<?php

namespace App\Models\Activity\ActivityPassReviewBonusFactory;
use App\Models\CarrierActivity;
use App\Models\CarrierActivityAudit;

/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 2017/4/25
 * Time: 下午5:41
 */
class ActivityPassReviewBonusFactory
{

    public static function createFactory(CarrierActivityAudit &$carrierActivity){
        switch ($carrierActivity->activity->bonuses_type){
            case CarrierActivity::BONUSER_TYPE_BETTING:
                return new ActivityPassReviewBonusYesterdayBetFlowFixedBonus($carrierActivity);
            case CarrierActivity::BONUSER_TYPE_FIXED_AMOUNT:
                return new ActivityPassReviewBonusDepositFixedBonus($carrierActivity);
            case CarrierActivity::BONUSER_TYPE_MEMBER_LEVEL:
                return new ActivityPassReviewBonusPlayerLevelDepositPercentage($carrierActivity);
            case CarrierActivity::BONUSER_TYPE_PERCENTAGE:
                return new ActivityPassReviewBonusPercentage($carrierActivity);
            case CarrierActivity::BONUSER_TYPE_POSITVE:
                return new ActivityPassReviewBonusYesterdayWinLosePercentage($carrierActivity);
            default:
                throw new \InvalidArgumentException('不合法的活动类型');
        }
    }

}

