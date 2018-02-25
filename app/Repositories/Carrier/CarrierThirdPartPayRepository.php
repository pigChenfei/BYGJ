<?php

namespace App\Repositories\Carrier;

use App\Models\Conf\CarrierThirdPartPay;
use InfyOm\Generator\Common\BaseRepository;

class CarrierThirdPartPayRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'carrier_id',
        'pay_channel_id',
        'merchant_number',
        'merchant_bind_domain'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierThirdPartPay::class;
    }
}
