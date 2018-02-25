<?php

namespace App\Repositories\Web;

use App\Models\Player;
use InfyOm\Generator\Common\BaseRepository;

class PlayerRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'mobile',
        'real_name',
        'user_name',
        'password',
        'pay_password',
        'email',
        'score',
        'account_money',
        'login_time',
        'delete_time',
        'login_ip',
        'operator_id',
        'agent_id',
        'is_online',
        'user_status',
        'remark',
        'level_id',
        'qq_account',
        'birthday',
        'register_ip'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Player::class;
    }
}
