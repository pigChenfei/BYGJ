<?php

namespace App\Repositories\Carrier;

use App\Models\Log\PlayerWithdrawFlowLimitLog;
use InfyOm\Generator\Common\BaseRepository;

class PlayerWithdrawFlowLimitLogRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'carrier_id',
        'player_id',
        'player_account_log',
        'limit_amount',
        'complete_limit_amount',
        'is_finished',
        'operator_id',
        'related_activity'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PlayerWithdrawFlowLimitLog::class;
    }
}
