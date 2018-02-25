<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 17/02/27
 * Time: 16:26
 */
namespace App\Http\Middleware;
use App\Http\Controllers\AppBaseController;
use App\Models\RolesModel\Permission;
use App\Models\RolesModel\Role;
use App\Models\RolesModel\RoleUser;
use Closure;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Guard;

class RBACAuthenticated {


    /**
     * @var SessionGuard
     */
    protected $auth;

    /**
     * Creates a new instance of the middleware.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }


    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure callable $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //如果是超级管理员直接不处理权限
        if($request->user() && $request->user()->is_super_admin){
            return $next($request);
        }
//        if ($this->auth->guest() || !$request->user()->can($request->route()->getAction()['controller'])) {
        //先判断是否登陆  权限都没添加到表中 后期优化
        if ($this->auth->guest()) {
            return AppBaseController::sendError('该用户无权限',403);
        }
        return $next($request);
    }

}
