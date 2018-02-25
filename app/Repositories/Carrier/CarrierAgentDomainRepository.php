<?php

namespace App\Repositories\Carrier;

use App\Models\CarrierAgentDomain;
use InfyOm\Generator\Common\BaseRepository;

class CarrierAgentDomainRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'agent_id',
        'website'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierAgentDomain::class;
    }
    
}
