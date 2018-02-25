<?php

namespace App\Repositories\Carrier;

use App\Models\Player;
use InfyOm\Generator\Common\BaseRepository;

class PlayerRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_name',
        'mobile',
        'real_name',
        'password',
        'pay_password',
        'email',
        'score',
        'main_account_amount',
        'login_ip',
        'agent_id',
        'player_level_id',
        'is_online',
        'level_id',
        'user_status',
        'password_wrong_times',
        'password_wrong_time',
        'login_domain',
        'qq_account',
        'birthday',
        'register_ip',
        'login_at',
        'remark'
    ];


    /**
     * Configure the Model
     **/
    public function model()
    {
        return Player::class;
    }
}
