<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 17/3/7
 * Time: 下午3:45
 */

namespace App\Repositories\Carrier;
use App\Criteria\Carrier\CarrierPlayerGamePlatRebateFinancialFlowCriteria;
use App\Models\CarrierPlayerGamePlatRebateFinancialFlow;
use InfyOm\Generator\Common\BaseRepository;


class CarrierPlayerGamePlatRebateFinancialFlowRepository extends BaseRepository
{

    public function boot()
    {

    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierPlayerGamePlatRebateFinancialFlow::class;
    }


}