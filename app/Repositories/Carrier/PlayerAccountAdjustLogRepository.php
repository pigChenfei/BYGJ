<?php

namespace App\Repositories\Carrier;

use App\Models\Log\PlayerAccountAdjustLog;
use InfyOm\Generator\Common\BaseRepository;

class PlayerAccountAdjustLogRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'player_id',
        'adjust_type',
        'operater',
        'amount',
        'remark'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PlayerAccountAdjustLog::class;
    }
}
