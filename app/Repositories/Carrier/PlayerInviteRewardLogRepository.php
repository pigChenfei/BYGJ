<?php

namespace App\Repositories\Carrier;

use App\Models\Log\PlayerInviteRewardLog;
use InfyOm\Generator\Common\BaseRepository;

class PlayerInviteRewardLogRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'carrier_id',
        'player_id',
        'reward_type',
        'reward_related_player',
        'reward_amount',
        'related_player_deposit_amount',
        'related_player_bet_amount',
        'related_player_validate_bet_amount'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PlayerInviteRewardLog::class;
    }
}
