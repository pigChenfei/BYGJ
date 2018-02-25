<?php

namespace App\Repositories\Carrier;

use App\Models\Conf\CarrierRegisterBasicConf;
use InfyOm\Generator\Common\BaseRepository;

class CarrierRegisterBasicConfRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'carrier_id',
        'player_birthday_conf_status',
        'player_realname_conf_status',
        'player_email_conf_status',
        'player_phone_conf_status',
        'player_qq_conf_status',
        'player_wechat_conf_status',
        'player_consignee_conf_status',
        'player_receiving_address_conf_status',
        'agent_type_conf_status',
        'agent_realname_conf_status',
        'agent_birthday_conf_status',
        'agent_email_conf_status',
        'agent_phone_conf_status',
        'agent_qq_conf_status',
        'agent_skype_conf_status',
        'agent_wechat_conf_status',
        'agent_promotion_mode_conf_status',
        'agent_promotion_url_conf_status',
        'agent_promotion_idea_conf_status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierRegisterBasicConf::class;
    }
}
