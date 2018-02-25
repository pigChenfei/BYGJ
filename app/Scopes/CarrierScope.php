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
class CarrierScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        try{
            //判断当前web环境是否是运营商后台
            if(\WinwinAuth::currentWebCarrier()){
                return $builder->where('carrier_id',\WinwinAuth::currentWebCarrier()->id);
            }
        }catch (\Exception $e){
            return $builder;
        }
        return $builder;
    }

}