<?php

namespace App\Repositories\Carrier;

use App\Models\CarrierUser;
use InfyOm\Generator\Common\BaseRepository;

class CarrierUserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'username',
        'password',
        'pwd_salt',
        'status',
        'parent_id',
        'mobile',
        'email'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierUser::class;
    }
}
