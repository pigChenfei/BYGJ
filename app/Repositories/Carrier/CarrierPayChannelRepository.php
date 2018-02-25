<?php
namespace App\Repositories\Carrier;

use App\Models\CarrierPayChannel;
use InfyOm\Generator\Common\BaseRepository;

class CarrierPayChannelRepository extends BaseRepository
{

    /**
     *
     * @var array
     */
    protected $fieldSearchable = [
        'carrier_id',
        'def_pay_channel_id',
        'binded_third_part_pay_id',
        'account',
        'owner_name',
        'display_name',
        'qrcode',
        'default_preferential_ratio',
        'balance_notify_amount',
        'status',
        'use_purpose',
        'card_origin_place',
        'remark',
        'show'
    ];

    /**
     * Configure the Model
     */
    public function model()
    {
        return CarrierPayChannel::class;
    }
}
