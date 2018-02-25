<?php
namespace App\Helpers\Privileges;
use App\Helpers\Caches\RouteCacheHelper;

/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 2017/4/28
 * Time: 下午9:26
 */
class PrivilegeHelper
{


    /**
     * @var \Illuminate\Routing\RouteCollection
     */
    private $routes;

    private static $instance;

    private function __construct()
    {
        $this->routes =  RouteCacheHelper::getAllCachedRoutes();
    }

    public static function instance(){
        if(self::$instance == null){
            self::$instance = new self();
        }
        return self::$instance;
    }


    /**
     * 是否有权限
     * @param array|string $routeName
     * @return bool
     */
    public function carrierUserHasPrivilege($routeName){
        //如果是超级管理员, 不需要鉴权
        if(\WinwinAuth::carrierUser()->is_super_admin){
            return true;
        }
        if(is_array($routeName)){
            foreach ($routeName as $route){
                if($this->carrierUserHasPrivilege($route) == false){
                    return false;
                }
            }
            return true;
        }
        if(!isset($this->routes[$routeName])){
            return false;
        }
        $controller = $this->routes[$routeName];
        if(\WinwinAuth::carrierUser()){
            return \WinwinAuth::carrierUser()->can($controller);
        }
        return false;
    }



}