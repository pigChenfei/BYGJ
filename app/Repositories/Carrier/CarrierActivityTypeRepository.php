<?php

namespace App\Repositories\Carrier;

use App\Models\CarrierActivityType;
use InfyOm\Generator\Common\BaseRepository;

class CarrierActivityTypeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'carrier_id',
        'type_name',
        'desc',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierActivityType::class;
    }
    
    /**
     * 获取活动类型数据
     * @param type $carrierid
     * @return type
     */
    public function allActivityType()
    {
        return $this->scopeQuery(function(){
            return $this->model->active()->OrderByDefault('desc');
        })->all();
    }
}
