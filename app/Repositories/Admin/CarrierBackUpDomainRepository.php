<?php

namespace App\Repositories\Admin;

use App\Models\CarrierBackUpDomain;
use InfyOm\Generator\Common\BaseRepository;

class CarrierBackUpDomainRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'carrier_id',
        'domain',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierBackUpDomain::class;
    }
}
