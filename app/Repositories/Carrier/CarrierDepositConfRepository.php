<?php

namespace App\Repositories\Carrier;

use App\Models\Conf\CarrierDepositConf;
use InfyOm\Generator\Common\BaseRepository;

class CarrierDepositConfRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'carrier_id',
        'is_allow_deposit',
        'is_allow_third_part_deposit_auto_arrival',
        'unreview_deposit_record_limit',
        'third_part_deposit_is_open',
        'company_deposit_is_open',
        'is_allow_company_deposit_auto_arrival',
        'virtual_card_deposit_is_open',
        'is_allow_virtual_card_deposit_auto_arrival'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierDepositConf::class;
    }
}
