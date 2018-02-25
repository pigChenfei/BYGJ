<?php

namespace App\Repositories\Carrier;

use App\Models\Log\PlayerBetFlowLog;
use InfyOm\Generator\Common\BaseRepository;

class PlayerBetFlowLogRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'player_id',
        'carrier_id',
        'game_plat_id',
        'game_id',
        'game_flow_code',
        'game_status',
        'bet_amount',
        'company_win_amount',
        'available_bet_amount',
        'company_payout_amount',
        'bet_flow_available',
        'progressive_bet',
        'progressive_win'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PlayerBetFlowLog::class;
    }
}
