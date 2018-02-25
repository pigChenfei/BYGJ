<?php
namespace App\Models\Conf;

use App\Models\Carrier;
use App\Scopes\CarrierScope;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Conf\CarrierDashLoginConf
 *
 * @property int $id 运营商登录注册类设置表
 * @property int $carrier_id 所属运营商
 * @property string $forbidden_login_comment 后台禁止登录提示原因
 * @property bool $carrier_login_failed_count_when_locked 后台登录错误导致锁定的次数 0不锁定
 * @property bool $is_allow_player_login 是否允许会员登录
 * @property bool $is_allow_player_register 是否允许会员注册
 * @property bool $player_login_failed_count_when_locked 会员登录失败锁定时的错误次数
 * @property string $player_register_forbidden_user_names 会员注册限制账号 逗号分隔多个账号
 * @property string $player_forbidden_login_comment 会员禁止登录原因
 * @property string $player_forbidden_register_comment 会员禁止注册原因
 * @property bool $is_check_exists_real_user_name 是否检测真实姓名是否同名
 * @property bool $is_allow_user_withdraw_with_password 是否允许会员取款时输入取款密码,如果允许 则取款时需要输入取款密码并且需要用户设置取款密码.
 * @property bool $is_allow_agent_login 是否允许代理登录
 * @property bool $is_allow_agent_register 是否允许代理注册
 * @property bool $agent_login_failed_count_when_locked 当代理登录失败锁定时的登录次数
 * @property string $agent_register_forbidden_user_names 代理注册禁止注册的账号列表 逗号分隔
 * @property string $agent_forbidden_login_comment 代理禁止登录原因
 * @property string $agent_forbidden_register_comment 代理禁止注册原因
 * @property bool $is_allow_agent_withdraw_with_password 是否允许代理取款时输入取款密码,如果允许 则取款时需要输入取款密码并且需要用户设置取款密码.
 * @property int $player_birthday_conf_status 会员生日配置项状态(0:无状态;1:显示;2:必填;多种情况下进行按位且运算判断
 * @property int $player_realname_conf_status 会员真实姓名配置项状态
 * @property int $player_email_conf_status 会员邮箱配置项状态
 * @property int $player_phone_conf_status 会员手机配置项状态
 * @property int $player_qq_conf_status 会员qq配置项状态
 * @property int $player_wechat_conf_status 会员微信配置项状态
 * @property int $player_consignee_conf_status 会员收货人配置项状态
 * @property int $player_receiving_address_conf_status 会员收货地址配置项状态
 * @property int $agent_type_conf_status 代理类型配置项状态
 * @property int $agent_realname_conf_status 代理真实姓名配置项状态
 * @property int $agent_birthday_conf_status 代理生日配置项状态
 * @property int $agent_email_conf_status 代理邮箱配置项状态
 * @property int $agent_phone_conf_status 代理手机配置项状态
 * @property int $agent_qq_conf_status 代理qq配置项状态
 * @property int $agent_skype_conf_status 代理skype配置项状态
 * @property int $agent_wechat_conf_status 代理微信配置项状态
 * @property int $agent_promotion_mode_conf_status 代理推广方式配置项状态
 * @property int $agent_promotion_url_conf_status 代理推广网址配置项状态
 * @property int $agent_promotion_idea_conf_status 代理推广想法配置项状态
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property bool $is_check_exist_player_real_user_name 是否检测会员真实姓名是否同名
 * @property bool $is_check_exist_agent_real_user_name 是否检测代理真实姓名是否存在
 */
class CarrierDashLoginConf extends Model
{

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'conf_carrier_register_login';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    const PLAYER_BIRTHDAY_CONF_STATUS = 'player_birthday_conf_status';

    const PLAYER_REALNAME_CONF_STATUS = 'player_realname_conf_status';

    const PLAYER_EMAIL_CONF_STATUS = 'player_email_conf_status';

    const PLAYER_PHONE_CONF_STATUS = 'player_phone_conf_status';

    const PLAYER_SEX_CONF_STATUS = 'player_sex_conf_status';

    const PLAYER_QQ_CONF_STATUS = 'player_qq_conf_status';

    const PLAYER_WECHAT_CONF_STATUS = 'player_wechat_conf_status';

    const AGENT_BIRTHDAY_CONF_STATUS = 'agent_birthday_conf_status';

    const AGENT_TYPE_CONF_STATUS = 'agent_type_conf_status';

    const AGENT_REALNAME_CONF_STATUS = 'agent_realname_conf_status';

    const AGENT_EMAIL_CONF_STATUS = 'agent_email_conf_status';

    const AGENT_PHONE_CONF_STATUS = 'agent_phone_conf_status';

    const AGENT_QQ_CONF_STATUS = 'agent_qq_conf_status';

    const AGENT_SKYPE_CONF_STATUS = 'agent_skype_conf_status';

    const AGENT_WECHAT_CONF_STATUS = 'agent_wechat_conf_status';

    const AGENT_PROMOTION_URL_CONF_STATUS = 'agent_promotion_url_conf_status';

    const AGENT_PROMOTION_IDEA_CONF_STATUS = 'agent_promotion_idea_conf_status';

    const IS_ALLOW_PLAYER_LOGIN = 'is_allow_player_login';

    const IS_ALLOW_PLAYER_REGISTER = 'is_allow_player_register';

    const IS_ALLOW_USER_WITHDRAW_WITH_PASSWORD = 'is_allow_user_withdraw_with_password';

    const IS_CHECK_EXISTS_REAL_USER_NAME = 'is_check_exist_player_real_user_name';

    const IS_ALLOW_AGENT_LOGIN = 'is_allow_agent_login';

    const IS_ALLOW_AGENT_REGISTER = 'is_allow_agent_register';

    const IS_ALLOW_AGENT_WITHDRAW_WITH_PASSWORD = 'is_allow_agent_withdraw_with_password';

    const IS_CHECK_EXIST_AGENT_REAL_USER_NAME = 'is_check_exist_agent_real_user_name';

    /**
     * 是
     */
    const STATUS_OPEN = 1;

    /**
     * 否
     */
    const STATUS_CLOSE = 0;

    // 是否必填
    const IS_REQUIRED = 2;

    // 是否显示
    const IS_DISPLAY = 1;

    public static function statusMeta()
    {
        return [
            self::STATUS_OPEN => '是',
            self::STATUS_CLOSE => '否'
        ];
    }

    public static function playerFieldAlias()
    {
        return [
            self::PLAYER_REALNAME_CONF_STATUS => '真实姓名',
            self::PLAYER_SEX_CONF_STATUS => '性别',
            self::PLAYER_BIRTHDAY_CONF_STATUS => '出生日期',
            self::PLAYER_EMAIL_CONF_STATUS => '电子邮件',
            self::PLAYER_PHONE_CONF_STATUS => '手机号码',
            self::PLAYER_QQ_CONF_STATUS => 'QQ',
            self::PLAYER_WECHAT_CONF_STATUS => '微信'
        ];
    }

    public static function agentFieldAlias()
    {
        return [
            self::AGENT_REALNAME_CONF_STATUS => '真实姓名',
            self::AGENT_BIRTHDAY_CONF_STATUS => '出生日期',
            self::AGENT_EMAIL_CONF_STATUS => '电子邮件',
            self::AGENT_PHONE_CONF_STATUS => '手机号码',
            self::AGENT_QQ_CONF_STATUS => 'QQ',
            self::AGENT_WECHAT_CONF_STATUS => '微信',
            self::AGENT_SKYPE_CONF_STATUS => 'Skype',
            self::AGENT_TYPE_CONF_STATUS => '代理类型',
            self::AGENT_PROMOTION_URL_CONF_STATUS => '推广网址',
            self::AGENT_PROMOTION_IDEA_CONF_STATUS => '邀请介绍'
        ];
    }

    public static function playerStatus()
    {
        return [
            self::IS_ALLOW_PLAYER_LOGIN => '是否允许会员登录',
            self::IS_ALLOW_PLAYER_REGISTER => '是否允许会员注册',
            self::IS_ALLOW_USER_WITHDRAW_WITH_PASSWORD => '是否需要取款密码',
            self::IS_CHECK_EXISTS_REAL_USER_NAME => '检测姓名是否同名'
        ];
    }

    public static function agentStatus()
    {
        return [
            self::IS_ALLOW_AGENT_LOGIN => '是否允许代理登录',
            self::IS_ALLOW_AGENT_REGISTER => '是否允许代理注册',
            self::IS_ALLOW_AGENT_WITHDRAW_WITH_PASSWORD => '是否需要取款密码',
            self::IS_CHECK_EXIST_AGENT_REAL_USER_NAME => '检测姓名是否同名'
        ];
    }

    public $fillable = [
        'carrier_id',
        'forbidden_login_comment',
        'carrier_login_failed_count_when_locked',
        'is_allow_player_login',
        'is_allow_player_register',
        'player_login_failed_count_when_locked',
        'player_login_failed_locked_time',
        'player_register_forbidden_user_names',
        'player_forbidden_login_comment',
        'player_forbidden_register_comment',
        'is_check_exist_player_real_user_name',
        'is_check_exist_agent_real_user_name',
        'is_allow_user_withdraw_with_password',
        'is_allow_agent_login',
        'is_allow_agent_register',
        'agent_login_failed_count_when_locked',
        'agent_login_failed_locked_time',
        'agent_register_forbidden_user_names',
        'agent_forbidden_login_comment',
        'agent_forbidden_register_comment',
        'is_allow_agent_withdraw_with_password',
        'player_birthday_conf_status',
        'player_realname_conf_status',
        'player_email_conf_status',
        'player_phone_conf_status',
        'player_sex_conf_status',
        'player_qq_conf_status',
        'player_wechat_conf_status',
        'agent_type_conf_status',
        'agent_realname_conf_status',
        'agent_birthday_conf_status',
        'agent_email_conf_status',
        'agent_phone_conf_status',
        'agent_qq_conf_status',
        'agent_skype_conf_status',
        'agent_wechat_conf_status',
        'agent_promotion_url_conf_status',
        'agent_promotion_idea_conf_status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'forbidden_login_comment' => 'string',
        'player_register_forbidden_user_names' => 'string',
        'player_forbidden_login_comment' => 'string',
        'player_forbidden_register_comment' => 'string',
        'agent_register_forbidden_user_names' => 'string',
        'agent_forbidden_login_comment' => 'string',
        'agent_forbidden_register_comment' => 'string',
        'player_birthday_conf_status' => 'integer',
        'player_realname_conf_status' => 'integer',
        'player_email_conf_status' => 'integer',
        'player_phone_conf_status' => 'integer',
        'player_sex_conf_status' => 'integer',
        'player_qq_conf_status' => 'integer',
        'player_wechat_conf_status' => 'integer',
        'agent_type_conf_status' => 'integer',
        'agent_realname_conf_status' => 'integer',
        'agent_birthday_conf_status' => 'integer',
        'agent_email_conf_status' => 'integer',
        'agent_phone_conf_status' => 'integer',
        'agent_qq_conf_status' => 'integer',
        'agent_skype_conf_status' => 'integer',
        'agent_wechat_conf_status' => 'integer',
        'agent_promotion_mode_conf_status' => 'integer',
        'agent_promotion_url_conf_status' => 'integer',
        'agent_promotion_idea_conf_status' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
    ];

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     */
    public function infCarrier()
    {
        return $this->belongsTo(Carrier::class, 'id', 'carrier_id');
    }
}
