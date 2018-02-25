<?php

namespace App\Models\RolesModel;

use App\Models\RolesModel\Permission;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;

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
 * @property string $group_name 权限分组名称
 * @property int $sort 排序
 * @property int $parent_id 父分组ID
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RolesModel\PermissionGroup[] $groups
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RolesModel\Permission[] $permissions
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RolesModel\PermissionGroup topGroup()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RolesModel\PermissionGroup orderBySort($type)
 */
class PermissionGroup extends Model
{

    public $table = 'permission_group';
    
    public $timestamps = true;

    public $fillable = [
        'group_name',
        'sort',
        'parent_id',
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
        'group_name' => 'string',
        'sort' => 'integer',
        'parent_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];


    protected $hidden = ['created_at','updated_at'];

    public function scopeTopGroup(Builder $query)
    {
        return $query->where('parent_id', 0);
    }

    public function scopeOrderBySort(Builder $query,$type){
        return $query->orderBy('sort',$type);
    }
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public function permissions(){
        return $this->hasMany(Permission::class,'group_id','id');
    }

    public function groups(){
        return $this->hasMany(PermissionGroup::class,'parent_id','id');
    }
}
