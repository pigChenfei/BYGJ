<?php

namespace App\Repositories\Carrier;

use App\Models\Log\PlayerAccountLog;
use InfyOm\Generator\Common\BaseRepository;

class PlayerAccountLogRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'player_id',
        'game_plat_id',
        'amount',
        'fund_type',
        'fund_source',
        'remark',
        'operator_reviewer_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PlayerAccountLog::class;
    }
}
