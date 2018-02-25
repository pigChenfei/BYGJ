<?php

namespace App\Http\Middleware;

use App\Console\Schedules\PlayerInviteRewardSchedule;
use App\Models\Conf\CarrierInvitePlayerConf;
use App\Models\Log\PlayerInviteRewardLog;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AppBaseController;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return AppBaseController::sendError('用户未登录', 403);
            }
            if($guard == 'member'){
                return redirect()->guest('/');
            }
            if($guard == 'agent'){
                return redirect()->guest('agents.index');
            }
            return redirect()->guest(( \App::environment() != 'production' ? $guard : '' ).'/login');
        }else{
            if($guard == 'member' && \WinwinAuth::memberUser()->is_online == false){
                Auth::guard($guard)->logout();
                return redirect()->guest('/');
            }
        }
        return $next($request);
    }
}
