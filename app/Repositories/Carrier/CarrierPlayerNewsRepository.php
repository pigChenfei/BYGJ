<?php

namespace App\Repositories\Carrier;

use App\Models\CarrierPlayerNews;
use InfyOm\Generator\Common\BaseRepository;

class CarrierPlayerNewsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'carrier_id',
        'player_id',
        'remark'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierPlayerNews::class;
    }
}
