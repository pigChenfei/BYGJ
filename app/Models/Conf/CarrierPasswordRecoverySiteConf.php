<?php

namespace App\Models\Conf;

use App\Scopes\CarrierScope;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CarrierPasswordRecoverySiteConf
 *
 * @package App\Models\Conf
 * @version March 23, 2017, 5:10 am UTC
 * @property int $id
 * @property int $carrier_id 运营商ID
 * @property bool $is_open_email_send_function 是否启用邮件发送功能
 * @property string $smtp_server smtp服务器
 * @property int $smtp_service_port smtp服务器端口
 * @property string $mail_sender 邮件发送人
 * @property string $smtp_username smtp账号
 * @property string $smtp_password smtp密码
 * @property string $smtp_encryption smtp加密
 * @property string $smtp_driver 邮件引擎
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierPasswordRecoverySiteConf whereCarrierId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierPasswordRecoverySiteConf whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierPasswordRecoverySiteConf whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierPasswordRecoverySiteConf whereIsOpenEmailSendFunction($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierPasswordRecoverySiteConf whereMailSender($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierPasswordRecoverySiteConf whereSmtpPassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierPasswordRecoverySiteConf whereSmtpServer($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierPasswordRecoverySiteConf whereSmtpServicePort($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierPasswordRecoverySiteConf whereSmtpUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierPasswordRecoverySiteConf whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CarrierPasswordRecoverySiteConf extends Model
{
    //use SoftDeletes;
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'conf_carrier_password_recovery_site';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     *是
     */
    const STATUS_OPEN = 1;
    /**
     *否
     */
    const STATUS_CLOSE = 0;

    public static function statusMeta(){
        return [
            self::STATUS_OPEN => '是',
            self::STATUS_CLOSE => '否',
        ];
    }


    protected $dates = ['deleted_at'];


    public $fillable = [
        'carrier_id',
        'is_open_email_send_function',
        'smtp_server',
        'smtp_service_port',
        'mail_sender',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'smtp_driver',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'smtp_server' => 'string',
        'smtp_service_port' => 'integer',
        'mail_sender' => 'string',
        'smtp_username' => 'string',
        'smtp_password' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
