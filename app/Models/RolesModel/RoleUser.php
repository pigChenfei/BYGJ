<?php

namespace App\Models\RolesModel;

use Eloquent as Model;

/**
 * Class RoleUser
 *
 * @package App\Models\RolesModel
 * @property integer $user_id
 * @property integer $role_id
 * @mixin \Eloquent
 */
class RoleUser extends Model
{

    public $table = 'role_user';
    
    public $timestamps = false;

    protected $primaryKey = 'role_id';

    public $fillable = [
        'user_id',
        'role_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'role_id' => 'integer'
    ];

    public function role(){
        return $this->hasOne(Role::class,'id','role_id');
    }

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id'  => 'required|exists:inf_prof_user,user_id',
        'role_id'  => 'required|exists:inf_admin_roles,id'
    ];
}
