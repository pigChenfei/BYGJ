<?php

namespace App\Models;

use App\Models\Conf\CarrierDashLoginConf;
use App\Models\RolesModel\Permission;
use App\Models\RolesModel\PermissionRole;
use App\Models\RolesModel\Role;
use App\Models\RolesModel\RoleUser;
use App\Scopes\CarrierScope;
use App\Traits\WinwinEntrustUserTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Auth;
use App\Models\Carrier;
use Illuminate\Notifications\Notifiable;


/**
 * App\Models\CarrierUser
 *
 * @property int $id
 * @property int $carrier_id 所属运营商
 * @property int $team_id 所属部门ID
 * @property string $username 账号
 * @property string $password 密码
 * @property string $pwd_salt
 * @property int $status 状态 1:正常,0: 已锁定, -1冻结
 * @property int $parent_id 父ID
 * @property string $mobile 手机号
 * @property string $email 邮箱
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property string $remember_token
 * @property-read \App\Models\Carrier $carrier
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RolesModel\PermissionRole[] $permissionRoles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RolesModel\Role[] $roles
 * @property-read \App\Models\CarrierServiceTeam $teamNames
 * @mixin \Eloquent
 * @property string $login_at 最近登录时间
 * @property bool $is_super_admin 是否是超级管理员, 具备所有权限
 * @property-read \App\Models\Conf\CarrierDashLoginConf $dashLoginConf
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \App\Models\CarrierServiceTeam $serviceTeam
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierUser byCarrierId($carrierId)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierUser superAdmin()
 */
class CarrierUser extends Auth
{

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    use WinwinEntrustUserTrait { restore as private restoreA; }
    use SoftDeletes { restore as private restoreB; }

    const CARRIER_ADMIN_ROLE_ID = 1;

    use Notifiable;

    /**
     * 解决 EntrustUserTrait 和 SoftDeletes 冲突
     */
    public function restore()
    {
        $this->restoreA();
        $this->restoreB();
    }



    /**
     * @var string
     */
    public $table = 'inf_carrier_user';

    /**
     *
     */
    const CREATED_AT = 'created_at';
    /**
     *
     */
    const UPDATED_AT = 'updated_at';

    /**
     *正常状态
     */
    const STATUS_NORMAL = 1;
    /**
     *锁定状态
     */
    const STATUS_LOCK = 0;
    /**
     *冻结状态
     */
    const STATUS_FREEZE = -1;

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $hidden = ['password','pwd_salt','remember_token','is_super_admin'];

    /**
     * 权限认证类型
     * @var string
     */
    public $entrustType = 'carrier';


    /**
     * @var array
     */
    public $fillable = [
        'carrier_id',
        'team_id',
        'username',
        'password',
        'pwd_salt',
        'status',
        'mobile',
        'email',
        'login_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'carrier_id' => 'integer',
        'team_id' => 'integer',
        'status' => 'integer',
        'username' => 'string',
        'password' => 'string',
        'pwd_salt' => 'string',
        'mobile' => 'string',
        'email' => 'string',
        'login_at'=>'string',
        'is_super_admin' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'team_id' => 'integer|max:1000000|required',
        'status'  => 'boolean|required',
    ];

    public static $requestAttributes = [
        'username'=> '客服账号',
        'password' => '密码',
        'team_id' => '所属部门',
        'status' => '状态',
    ];


    public function scopeByCarrierId(Builder $query, $carrierId){
        return $query->where('carrier_id',$carrierId);
    }

    /**
     * 是否是运营商管理员
     * @return bool
     */
    public function isCarrierAdmin(){
        foreach ($this->roles as $role){
            if($role->role_id == self::CARRIER_ADMIN_ROLE_ID){
                return true;
            }
        }
        return false;
    }

    public function scopeSuperAdmin(Builder $query){
        return $query->where('is_super_admin', true);
    }

    /**
     * 用户是否正常
     * @return bool
     */
    public function isNormal(){
        if($this->carrier->isActive() == false){
            return false;
        }
        return $this->status == self::STATUS_NORMAL;
    }

    /**
     * 用户是否被禁用了
     * @return bool
     */
    public function isForbidden (){
        if($this->carrier->isActive() == false){
            return false;
        }
        return $this->status == self::STATUS_FREEZE || $this->status == self::STATUS_LOCK;
    }

    /**
     * 用户账户是否被锁定
     * @return bool
     */
    public function isLocked(){
        if($this->carrier->isActive() == false){
            return false;
        }
        return $this->status == self::STATUS_LOCK;
    }


    public static function statusMeta(){
        return [
            self::STATUS_NORMAL => '正常',
            self::STATUS_LOCK =>'锁定',
            self::STATUS_FREEZE => '冻结',

        ];
    }

    public static function createRules($current_carrier_id){
        return array_merge(self::$rules,[
            'username' => 'required|max:20|unique:inf_carrier_user,username,NULL,id,carrier_id,'.$current_carrier_id,
            'password' => 'required|alpha_num|max:20',
        ]);
    }

    public static function updateRules($current_carrier_id,$except_id){
        return array_merge(self::$rules,[
            'username' => 'required|max:20|unique:inf_carrier_user,username,'.$except_id.',id,carrier_id,'.$current_carrier_id,
        ]);
    }

    public function serviceTeam(){
        return $this->belongsTo(CarrierServiceTeam::class,'team_id','id');
    }

    public function permissionRoles(){
        return $this->hasManyThrough(PermissionRole::class,RoleUser::class,'user_id','role_id');
    }

    public function carrier(){
        return $this->belongsTo(Carrier::class,'carrier_id','id');
    }

    public function scopeNoSuperAdministrator(Builder $query){
        return $query->where('is_super_admin', false);
    }

    public function dashLoginConf(){
        return $this->belongsTo(CarrierDashLoginConf::class,'carrier_id','carrier_id');
    }
}
