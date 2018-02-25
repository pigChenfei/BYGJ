<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 2017/4/8
 * Time: 下午2:53
 */

namespace App\Scopes;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PlayerScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        try{
            //判断当前是个人登录的后台
            if((\WinwinAuth::isCurrentMemberFrontEndSystem() || \WinwinAuth::isCurrentMemberAdminSystem()) && \WinwinAuth::memberUser()){
                return $builder->where('player_id',\WinwinAuth::memberUser()->player_id);
            }
        }catch (\Exception $e){
            return $builder;
        }
        return $builder;
    }

}