<?php

namespace App\Repositories\Carrier;

use App\Models\CarrierGroup;
use InfyOm\Generator\Common\BaseRepository;

class CarrierGroupRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'status',
        'rules',
        'operator_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierGroup::class;
    }
}
