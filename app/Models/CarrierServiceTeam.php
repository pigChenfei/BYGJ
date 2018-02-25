<?php

namespace App\Models;

use App\Models\RolesModel\Permission;
use App\Models\RolesModel\PermissionGroup;
use App\Scopes\CarrierScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\CarrierServiceTeam
 *
 * @property int $id 部门ID
 * @property int $carrier_id 运营商ID
 * @property string $team_name 部门名称
 * @property bool $is_administrator 是否是管理员部门
 * @property string $remark 部门备注信息
 * @property bool $status 状态1正常;0关闭
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RolesModel\Permission[] $teamPermissions
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierServiceTeam administrator()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierServiceTeam byCarrierId($carrier_id)
 * @mixin \Eloquent
 */
class CarrierServiceTeam extends Model
{

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }
    //use SoftDeletes;

    public $table = 'inf_carrier_service_team';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    /**
     *正常状态
     */
    const STATUS_NORMAL = 1;

    /**
     *关闭状态
     */
    const STATUS_CLOSE = 0;


    protected $dates = ['deleted_at'];


    public $fillable = [
        'carrier_id',
        'team_name',
        'remark',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'team_name' => 'string',
        'remark' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'remark'=>'string',
    ];

    public static $requestAttributes = [
        'team_name' => '部门名称',
        'status' => '状态',
        'remark' => '备注'
    ];

    public static function createRules($current_carrier_id){
        return array_merge(self::$rules,[
            'team_name' => 'required|max:20|unique:inf_carrier_service_team,team_name,NULL,id,carrier_id,'.$current_carrier_id,
        ]);
    }

    public static function updateRules($current_carrier_id,$id){
        return array_merge(self::$rules,[
            'team_name' => 'required|max:20|unique:inf_carrier_service_team,team_name,'.$id.',id,carrier_id,'.$current_carrier_id,
        ]);
    }

    public static function statusMeta(){
        return [
            self::STATUS_NORMAL => '正常',
            self::STATUS_CLOSE => '关闭',
        ];
    }

    public function teamPermissions(){
        return $this->belongsToMany(Permission::class,'inf_carrier_service_team_role','team_id','permission_id');
    }

    public function teamRoles(){
        return $this->hasMany(CarrierServiceTeamRole::class,'team_id','id');
    }

    public function scopeByCarrierId(Builder $query,$carrier_id){
        return $query->where('carrier_id',$carrier_id);
    }

    public function scopeAdministrator(Builder $query){
        return $query->where('is_administrator',1);
    }

    public function scopeNoAdministrator(Builder $query){
        return $query->where('is_administrator',0);
    }

}
