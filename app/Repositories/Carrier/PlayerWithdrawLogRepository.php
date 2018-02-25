<?php

namespace App\Repositories\Carrier;

use App\Models\Log\PlayerWithdrawLog;
use InfyOm\Generator\Common\BaseRepository;

class PlayerWithdrawLogRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'order_number',
        'carrier_id',
        'player_id',
        'apply_amount',
        'finnally_withdraw_amount',
        'carrier_pay_channel',
        'player_bank_card',
        'status',
        'reviewed_at',
        'withdraw_succeed_at',
        'operator',
        'remark'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PlayerWithdrawLog::class;
    }
}
