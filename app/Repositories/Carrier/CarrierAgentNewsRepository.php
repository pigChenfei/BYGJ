<?php

namespace App\Repositories\Carrier;

use App\Models\CarrierAgentNews;
use InfyOm\Generator\Common\BaseRepository;

class CarrierAgentNewsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'carrier_id',
        'title',
        'remark',
        'operator_reviewer_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierAgentNews::class;
    }
}
