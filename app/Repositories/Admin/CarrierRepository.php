<?php

namespace App\Repositories\Admin;

use App\Models\Carrier;
use InfyOm\Generator\Common\BaseRepository;

class CarrierRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'site_url',
        'is_forbidden',
        'remain_quota'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Carrier::class;
    }
}
