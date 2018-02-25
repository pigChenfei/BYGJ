<?php

namespace App\Console\Schedules;
use App\Jobs\CarrierInviteRewardHandle;
use App\Models\Conf\CarrierInvitePlayerConf;
use App\Helpers\Caches\CarrierInfoCacheHelper;
use App\Models\Player;
use App\Models\Carrier;
use Carbon\Carbon;

/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 2017/4/19
 * Time: 下午1:32
 */
class PlayerInviteRewardSchedule
{
    public function run(){
         $carriers=Carrier::all();
         foreach($carriers as $carrier)
         {
            dispatch(new CarrierInviteRewardHandle($carrier));
         }
    }
}