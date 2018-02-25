<?php

namespace App\Repositories\Carrier;
use App\Models\CarrierAgentUser;
use InfyOm\Generator\Common\BaseRepository;

class CarrierAgentUserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'username',
        'password',
        'skype',
        'qq',
        'parent_id',
        'carrier',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierAgentUser::class;
    }
    
    /**
     * 获取所有代理用户信息
     * @param type $carrierid
     * @return type
     */
    public function allAgentUser()
    {
        return $this->scopeQuery(function(){
            return $this->model->active()->OrderByAgentUser('desc');
        })->all();
    }
    
}
