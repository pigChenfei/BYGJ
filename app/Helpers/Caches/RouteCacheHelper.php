<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 2017/4/28
 * Time: 下午9:35
 */

namespace App\Helpers\Caches;


use App\Entities\CacheConstantPrefixDefine;
use Illuminate\Routing\RouteCollection;

class RouteCacheHelper
{


    /**
     * @return array
     */
    public static function getAllCachedRoutes(){
        $role = \App::make('currentRole');
        return \Cache::remember(CacheConstantPrefixDefine::CARRIER_CACHED_ROUTE_LIST_PREFIX.$role,1440,function (){
            $controllers = [];
            foreach(\Route::getRoutes() as $item){
                if(($name = $item->getName())){
                    if(isset($item->getAction()['controller'])){
                        $controllers[$name] = $item->getAction()['controller'];
                    }
                }
            }
            return $controllers;
        });
    }


    public static function clearAllCachedRoutes($roleName = null){
        $roleName = $roleName ?: \App::make('currentRole');
        \Cache::forget(CacheConstantPrefixDefine::CARRIER_CACHED_ROUTE_LIST_PREFIX.$roleName);
    }

}