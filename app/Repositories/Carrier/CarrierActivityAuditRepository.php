<?php

namespace App\Repositories\Carrier;

use App\Models\CarrierActivityAudit;
use InfyOm\Generator\Common\BaseRepository;

class CarrierActivityAuditRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'act_id',
        'carrier_id',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierActivityAudit::class;
    }
}
