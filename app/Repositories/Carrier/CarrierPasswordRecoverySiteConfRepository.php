<?php

namespace App\Repositories\Carrier;

use App\Models\Conf\CarrierPasswordRecoverySiteConf;
use InfyOm\Generator\Common\BaseRepository;

class CarrierPasswordRecoverySiteConfRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'is_open_email_send_function',
        'smtp_ server',
        'smtp_service_port',
        'mail_sender',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'smtp_driver',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierPasswordRecoverySiteConf::class;
    }
}
