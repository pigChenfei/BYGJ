<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/15
 * Time: 下午2:24
 */
class CarrierAgentScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        try{
            //判断当前web环境是否是代理后台
            if(\WinwinAuth::currentWebCarrier()){
                $builder =  $builder->where('carrier_id',\WinwinAuth::currentWebCarrier()->id);
            }
            if(\WinwinAuth::currentWebAgent() && \WinwinAuth::currentWebAgent()->is_default == false){
                $builder =  $builder->where('agent_id',\WinwinAuth::currentWebAgent()->id);
            }
            return $builder;
        }catch (\Exception $e){
            return $builder;
        }
        return $builder;
    }

}