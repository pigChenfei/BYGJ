<?php

namespace App\Repositories\Carrier;

use App\Models\Log\CarrierAgentSettleLog;
use InfyOm\Generator\Common\BaseRepository;

class CarrierAgentSettleLogRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'carrier_id',
        'agent_id',
        'available_member_number',
        'game_plat_win_amount',
        'available_player_bet_amount',
        'cost_share',
        'cumulative_last_month',
        'manual_tuneup',
        'this_period_commission',
        'actual_payment',
        'transfer_next_month',
        'status',
        'remark',
        'created_user_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierAgentSettleLog::class;
    }
}
