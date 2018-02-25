<?php

namespace App\Repositories\Carrier;

use App\Models\Log\PlayerWithdrawFlowLimitLogDetail;
use InfyOm\Generator\Common\BaseRepository;

class PlayerWithdrawFlowLimitLogDetailRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'withdraw_flow_limit_id',
        'game_plat_id',
        'game_id',
        'flow_amount'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PlayerWithdrawFlowLimitLogDetail::class;
    }
}
