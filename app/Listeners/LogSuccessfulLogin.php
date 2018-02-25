<?php

namespace App\Listeners;

use App\Entities\CacheConstantPrefixDefine;
use App\Helpers\IP\RealIpHelper;
use App\Jobs\UpdateLogModelIpAddressQueue;
use App\Models\Log\PlayerLoginLog;
use App\Models\Player;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Redis;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        if($event->user instanceof Player){
            $log = new PlayerLoginLog();
            $log->player_id = $event->user->player_id;
            $log->carrier_id = $event->user->carrier_id;
            $log->login_ip = RealIpHelper::getIp();
            $log->login_domain = Request::capture()->getHost();
            $log->login_time   = Carbon::now();
            \Cache::put(CacheConstantPrefixDefine::MEMBER_USER_ONLINE_REMEMBER_CACHE_PREFIX.$log->player_id,1,\Config::get('session.lifetime'));
            $event->user->login_domain = $log->login_domain;
            $event->user->login_ip     = $log->login_ip;
            $event->user->login_at     = $log->login_time;
            $event->user->is_online = true;
            try{
                $event->user->save();
                $log->save();
            }catch(\Exception $e){

            }
        }
    }
}
