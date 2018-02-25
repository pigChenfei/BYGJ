<?php

namespace App\Models\RolesModel;

use Eloquent as Model;

/**
 * Class PermissionRole
 *
 * @package App\Models\RolesModel
 * @property integer $permission_id
 * @property integer $role_id
 * @mixin \Eloquent
 * @property-read \App\Models\RolesModel\Permission $permission
 * @property-read \App\Models\RolesModel\Role $role
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RolesModel\PermissionRole wherePermissionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RolesModel\PermissionRole whereRoleId($value)
 */
class PermissionRole extends Model
{

    public $table = 'permission_role';
    
    public $timestamps = false;

    public $fillable = [
        'permission_id',
        'role_id'
    ];

    public function delete()
    {
        return \DB::table($this->table)->where(
            [
                'permission_id' => $this->permission_id,
                'role_id'       => $this->role_id
            ]
        )->delete();
    }

    protected $hidden = ['created_at','updated_at'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'permission_id' => 'integer',
        'role_id' => 'integer'
    ];

    public function permission(){
        return $this->hasOne(Permission::class,'id','permission_id');
    }

    public function role(){
        return $this->hasOne(Role::class,'id','role_id');
    }

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
    ];
}
