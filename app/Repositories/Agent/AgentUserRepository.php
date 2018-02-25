<?php

namespace App\Repositories\Agent;
use App\Criteria\Agent\AgentUserSelectCriteria;
use App\Models\CarrierAgentUser;
use InfyOm\Generator\Common\BaseRepository;

class AgentUserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'username',
        'password',
        'skype',
        'qq',
        'tgurl',
        'parent_id',
        'carrier',
        'status'
    ];

    public function boot()
    {
        $this->pushCriteria(new AgentUserSelectCriteria());
    }
    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierAgentUser::class;
    }
    
}
