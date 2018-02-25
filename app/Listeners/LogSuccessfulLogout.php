<?php

namespace App\Listeners;

use App\Entities\CacheConstantPrefixDefine;
use App\Models\Player;
use Illuminate\Auth\Events\Logout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogSuccessfulLogout
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
     * @param  Logout  $event
     * @return void
     */
    public function handle(Logout $event)
    {
        //dd(\WinwinAuth::memberUser());
        if($user = \WinwinAuth::memberUser()){
            \Cache::forget(CacheConstantPrefixDefine::MEMBER_USER_ONLINE_REMEMBER_CACHE_PREFIX.$user->player_id);
            $user->is_online = false;
            try{
                $user->save();
            }catch(\Exception $e){

            }
        }
    }
}
