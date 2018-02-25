<?php

namespace App\Repositories\Carrier;

use App\Models\Conf\CarrierDashLoginConf;
use InfyOm\Generator\Common\BaseRepository;

class CarrierDashLoginConfRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'carrier_id',
        'forbidden_login_comment',
        'carrier_login_failed_count_when_locked',
        'is_allow_player_login',
        'is_allow_player_register',
        'player_login_failed_count_when_locked',
        'player_register_forbidden_user_names',
        'player_forbidden_login_comment',
        'player_forbidden_register_comment',
        'is_check_exists_real_user_name',
        'is_allow_user_edit_self_info',
        'is_allow_user_withdraw_with_password',
        'is_user_register_base_info_required',
        'is_user_register_telephone_required',
        'is_user_register_email_required',
        'is_allow_agent_login',
        'is_allow_agent_register',
        'agent_login_failed_count_when_locked',
        'agent_register_forbidden_user_names',
        'agent_forbidden_login_comment',
        'agent_forbidden_register_comment',
        'is_allow_agent_edit_self_info',
        'is_allow_agent_withdraw_with_password',
        'is_agent_register_base_info_required',
        'is_agent_register_telephone_required',
        'is_agent_register_email_required'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierDashLoginConf::class;
    }
}
