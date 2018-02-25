<?php

namespace App\Repositories\Carrier;

use App\Models\Def\PayChannelType;
use InfyOm\Generator\Common\BaseRepository;

class PayChannelTypeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type_name',
        'parent_id',
        'sort'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PayChannelType::class;
    }
}
