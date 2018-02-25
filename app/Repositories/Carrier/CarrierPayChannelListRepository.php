<?php

namespace App\Repositories\Carrier;

use App\Models\Def\PayChannel;
use InfyOm\Generator\Common\BaseRepository;

class CarrierPayChannelListRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'channel_name',
        'web_channel_name',
        'channel_code',
        'pay_channel_type_id',
        'is_need_private_key',
        'is_need_merchant_code',
        'sort'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PayChannel::class;
    }
}
