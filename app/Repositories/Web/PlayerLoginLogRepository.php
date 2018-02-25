<?php

namespace App\Repositories\Web;

use App\Models\Log\PlayerLoginLog;
use InfyOm\Generator\Common\BaseRepository;

class PlayerLoginLogRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'login_ip',
        'login_domain',
        'login_time',
        'login_location',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PlayerLoginLog::class;
    }
}
