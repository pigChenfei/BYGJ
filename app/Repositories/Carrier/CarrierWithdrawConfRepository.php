<?php

namespace App\Repositories\Carrier;

use App\Models\Conf\CarrierWithdrawConf;
use InfyOm\Generator\Common\BaseRepository;

class CarrierWithdrawConfRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'carrier_id',
        'is_allow_player_withdraw',
        'is_allow_player_withdraw_decimal',
        'player_day_withdraw_success_limit_count',
        'player_day_withdraw_max_sum',
        'player_once_withdraw_max_sum',
        'player_once_withdraw_min_sum',
        'is_diaplay_flow_water_check',
        'is_check_flow_water_when_withdraw',
        'is_allow_agent_withdraw',
        'is_allow_agent_withdraw_decimal',
        'agent_day_withdraw_success_limit_count',
        'agent_day_withdraw_max_sum',
        'agent_once_withdraw_max_sum',
        'agent_once_withdraw_min_sum'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierWithdrawConf::class;
    }
}
