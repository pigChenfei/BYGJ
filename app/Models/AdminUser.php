<?php

namespace App\Models;

use App\Traits\WinwinEntrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Auth;
/**
 * Class AdminUser
 *
 * @package App\Models
 * @version February 26, 2017, 1:41 pm UTC
 * @property int $user_id
 * @property string $username 账号
 * @property string $password
 * @property string $pwd_salt
 * @property string $mobile 手机号码
 * @property string $email 创建时间
 * @property bool $status
 * @property string $create_time 创建时间
 * @property string $last_login_time 最后一次登录时间
 * @property int $login_ip  登录IP
 * @property int $parent_id 父ID
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RolesModel\Role[] $roles
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereCreateTime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereLastLoginTime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereLoginIp($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereMobile($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser wherePwdSalt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereUsername($value)
 */
class AdminUser extends Auth
{
    use WinwinEntrustUserTrait;

    /**
     * @var string
     */
    public $table = 'inf_admin_user';

    /**
     *
     */
    const CREATED_AT = 'created_at';
    /**
     *
     */
    const UPDATED_AT = 'updated_at';

    /**
     * @var string
     */
    protected $primaryKey = 'user_id';


    /**
     * 权限认证类型
     * @var string
     */
    public $entrustType = 'admin';

    /**
     * @var array
     */
    public $fillable = [
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
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'username' => 'string',
        'password' => 'string',
        'pwd_salt' => 'string',
        'mobile' => 'string',
        'email' => 'string',
        'login_ip' => 'integer',
        'parent_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];


}
