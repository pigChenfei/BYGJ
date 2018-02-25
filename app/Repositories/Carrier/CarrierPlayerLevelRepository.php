<?php

namespace App\Repositories\Carrier;

use App\Models\CarrierPlayerLevel;
use Illuminate\Database\Eloquent\Builder;
use InfyOm\Generator\Common\BaseRepository;
use Prettus\Repository\Contracts\RepositoryInterface;

class CarrierPlayerLevelRepository extends BaseRepository implements RepositoryInterface
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'level_name',
        'remark',
        'is_default',
        'carrier_id',
        'status',
        'sort',
        'upgrade_rule'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierPlayerLevel::class;
    }

    public function allPlayerLevels()
    {
        return $this->scopeQuery(function(){
            return $this->model->active()->OrderByDefault('desc');
        })->all();
    }
}
