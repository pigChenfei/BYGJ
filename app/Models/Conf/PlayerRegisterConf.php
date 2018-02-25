<?php
namespace App\Models\Conf;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PlayerRegisterConf
 *
 * @package App\Models
 * @version March 25, 2017, 1:28 pm UTC
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
 * @property bool $is_allow_user_edit_self_info 是否允许会员编辑自己的基本信息
 * @property bool $is_allow_user_withdraw_with_password 是否允许会员取款时输入取款密码,如果允许 则取款时需要输入取款密码并且需要用户设置取款密码.
 * @property bool $is_user_register_base_info_required 会员注册是否基本信息必填 如 姓名, 生日, QQ号, 微信号
 * @property bool $is_user_register_telephone_required 会员注册是否手机号必填
 * @property bool $is_user_register_email_required 是否会员注册email必填
 * @property bool $is_allow_agent_login 是否允许代理登录
 * @property bool $is_allow_agent_register 是否允许代理注册
 * @property bool $agent_login_failed_count_when_locked 当代理登录失败锁定时的登录次数
 * @property string $agent_register_forbidden_user_names 代理注册禁止注册的账号列表 逗号分隔
 * @property string $agent_forbidden_login_comment 代理禁止登录原因
 * @property string $agent_forbidden_register_comment 代理禁止注册原因
 * @property bool $is_allow_agent_edit_self_info 是否允许代理编辑自己的基本信息
 * @property bool $is_allow_agent_withdraw_with_password 是否允许代理取款时输入取款密码,如果允许 则取款时需要输入取款密码并且需要用户设置取款密码.
 * @property bool $is_agent_register_base_info_required 代理注册是否基本信息必填 如 姓名, 生日, QQ号, 微信号
 * @property bool $is_agent_register_telephone_required 代理注册是否手机号必填
 * @property bool $is_agent_register_email_required 是否代理注册email必填
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at @mixin \Eloquent
 */
class PlayerRegisterConf extends Model
{

    // use SoftDeletes;
    public $table = 'conf_carrier_register_login';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $dates = [
        'deleted_at'
    ];

    public $fillable = [
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
        'agent_forbidden_register_comment' => 'string'
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
    /*
     * public function infCarrier()
     * {
     * return $this->belongsTo(\App\Models\InfCarrier::class);
     * }
     */
}
