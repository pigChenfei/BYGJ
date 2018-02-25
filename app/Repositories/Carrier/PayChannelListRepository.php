<?php

namespace App\Repositories\Carrier;

use App\Models\Def\PayChannel;
use InfyOm\Generator\Common\BaseRepository;

class PayChannelRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'channel_name',
        'channel_code',
        'pay_channel_type_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PayChannel::class;
    }
}
