<?php

namespace App\Repositories\Carrier;

use App\Models\Map\CarrierGamePlat;
use InfyOm\Generator\Common\BaseRepository;

class CarrierGamePlatRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'carrier_id',
        'status',
        'game_plat_id',
        'display_plat_name',
        'sort'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierGamePlat::class;
    }
}
