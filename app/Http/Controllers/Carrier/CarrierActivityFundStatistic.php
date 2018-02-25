<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 2017/4/26
 * Time: 下午3:45
 */

namespace App\Http\Controllers\Carrier;


use App\DataTables\Carrier\CarrierActivityFundDataTable;
use App\Http\Controllers\AppBaseController;
use App\Repositories\Carrier\CarrierActivityRepository;

class CarrierActivityFundStatistic extends AppBaseController
{

    /** @var  CarrierActivityRepository */
    private $carrierActivityRepository;

    public function __construct(CarrierActivityRepository $carrierActivityRepo)
    {
        $this->carrierActivityRepository = $carrierActivityRepo;
    }

    public function index(CarrierActivityFundDataTable $carrierActivityDataTable){
        return $carrierActivityDataTable->render('Carrier.carrier_activity_fund.index');
    }

}