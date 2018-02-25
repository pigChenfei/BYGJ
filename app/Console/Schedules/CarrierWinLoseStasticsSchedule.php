<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 2017/5/2
 * Time: 下午10:07
 */
namespace App\Console\Schedules;

use App\Jobs\CarrierWinLoseStastics;
use App\Jobs\GameWinLoseStasticsJob;
use App\Models\Carrier;
use App\Models\Map\CarrierGamePlat;
use App\Jobs\AgentWinLoseStasticsJob;
use App\Models\CarrierAgentUser;

class CarrierWinLoseStasticsSchedule
{

    public function run()
    {
        Carrier::get([
            'id'
        ])->each(
            function (Carrier $carrier) {
                dispatch(new CarrierWinLoseStastics($carrier->id));
                $gamePlat = $carrier->mapGamePlats;
                $gamePlat->each(
                    function (CarrierGamePlat $gamePlat) use ($carrier) {
                        dispatch(new GameWinLoseStasticsJob($carrier->id, $gamePlat->game_plat_id));
                    });
                $agents = $carrier->agents;
                $agents->each(
                    function (CarrierAgentUser $agent) use ($carrier) {
                        if ($agent->is_default == 0) {
                            dispatch(new AgentWinLoseStasticsJob($carrier->id, $agent->id));
                        }
                    });
            });
    }
}