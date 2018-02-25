<?php

namespace App\Repositories\Admin;

use App\Models\AdminUser;
use InfyOm\Generator\Common\BaseRepository;

class AdminUserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'username',
        'password',
        'pwd_salt',
        'mobile',
        'email',
        'status',
        'create_time',
        'last_login_time',
        'login_ip',
        'parent_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return AdminUser::class;
    }
}
