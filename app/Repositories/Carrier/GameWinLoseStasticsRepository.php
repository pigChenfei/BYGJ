<?php

namespace App\Repositories\Carrier;

use App\Models\Log\GameWinLoseStastics;
use InfyOm\Generator\Common\BaseRepository;

class GameWinLoseStasticsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'carrier_id',
        'game_plat_id',
        'bet_player_count',
        'bet_count',
        'bet_amount',
        'win_lose_amount',
        'rebate_financial_flow_amount',
        'average_bet_amount',
        'average_bet_count'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return GameWinLoseStastics::class;
    }
}
