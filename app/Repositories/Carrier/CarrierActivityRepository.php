<?php

namespace App\Repositories\Carrier;

use App\Models\CarrierActivity;
use InfyOm\Generator\Common\BaseRepository;

class CarrierActivityRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'carrier_id',
        'act_type_id',
        'image_id',
        'name',
        'status',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierActivity::class;
    }
    
    public function allActivitylist()
    {
        return $this->scopeQuery(function(){
            return $this->model->active();
        })->all();
    }
}
