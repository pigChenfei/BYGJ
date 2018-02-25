<?php

namespace App\Models\RolesModel;

use Zizaco\Entrust\EntrustRole;

/**
 * Class Role
 *
 * @package App\Models\RolesModel
 * @property integer $id
 * @property string $name
 * @property string $display_name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property string $role_level
 * @mixin \Eloquent
 */
class Role extends EntrustRole
{

    public $table = 'roles';
    
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
        'updated_at' => 'datetime',
    ];

    protected $hidden = ['created_at','updated_at','role_level'];

    public function permissions(){
        return $this->hasMany(PermissionRole::class,'role_id','id');
    }
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];
}
