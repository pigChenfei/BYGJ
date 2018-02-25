<?php

namespace App\Repositories\Agent;

use App\Models\AgentCenter;
use InfyOm\Generator\Common\BaseRepository;

class AgentCenterRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'username',
        'password',
        'realname',
        'agent_level_id',
        'amount',
        'player_number',
        'skype',
        'qq',
        'wechat',
        'mobile',
        'email',
        'promotion_code',
        'card_account',
        'card_type',
        'card_owner_name',
        'card_birth_place',
        'parent_id',
        'carrier_id',
        'status',
        'audit_status',
        'is_default',
        'customer_remark',
        'customer_time',
        'login_time',
        'register_ip',
        'remember_token'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return AgentCenter::class;
    }
}
