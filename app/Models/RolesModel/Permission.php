<?php

namespace App\Models\RolesModel;

use App\Models\CarrierServiceTeamRole;
use Eloquent as Model;

/**
 * Class Permission
 *
 * @package App\Models\RolesModel
 * @property integer $id
 * @property string $name
 * @property string $display_name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RolesModel\PermissionRole[] $roles
 */
class Permission extends Model
{

    public $table = 'permissions';
    
    public $timestamps = true;

    public $fillable = [
        'name',
        'display_name',
        'description',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'display_name' => 'string',
        'description' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function roles(){
        return $this->hasMany(PermissionRole::class,'permission_id','id');
    }


    protected $hidden = ['created_at','updated_at'];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|unique:inf_admin_permissions',
        'display_name' => 'required|unique:inf_admin_permissions',
        'description' => 'required|unique:inf_admin_permissions'
    ];

    public function permissionGroup(){
        return $this->belongsTo(PermissionGroup::class,'group_id','id');
    }
}
