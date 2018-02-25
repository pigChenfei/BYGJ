<?php

namespace App\Models;

use App\Models\RolesModel\Permission;
use App\Scopes\CarrierScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class PermissionRole
 *
 * @package App\Models\RolesModel
 * @property integer $permission_id
 * @property integer $role_id
 * @mixin \Eloquent
 * @property-read \App\Models\RolesModel\Permission $permission
 * @property-read \App\Models\RolesModel\Role $role
 * @property int $team_id 运营商客服部门ID
 * @property int $carrier_id
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierServiceTeamRole whereCarrierId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierServiceTeamRole wherePermissionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierServiceTeamRole whereTeamId($value)
 */
class CarrierServiceTeamRole extends Model
{

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'inf_carrier_service_team_role';
    
    public $timestamps = false;

    public $fillable = [
        'permission_id',
        'team_id',
        'carrier_id'
    ];



    protected $hidden = ['created_at','updated_at'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [

        'permission_id' => 'integer',
        'team_id' => 'integer'
    ];

    public function scopePermissionIds(Builder $query, $permissionIds){
        return $query->whereIn('permission_id',$permissionIds);
    }

    public function scopeByCarrierId(Builder $query, $carrierId){
        return $query->where('carrier_id',$carrierId);
    }

//    public function permission(){
//        return $this->hasOne(Permission::class,'id','permission_id');
//    }
//
//    public function role(){
//        return $this->hasOne(CarrierServiceTeamRole::class,'id','team_id');
//    }

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
}
