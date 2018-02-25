<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 2017/4/5
 * Time: 下午2:51
 */

namespace App\Providers;
use App\Services\WinwinScheduleLogService;
use Illuminate\Support\ServiceProvider;

class WinwinScheduleLogServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        //
        $this->app->singleton('WLog',function($app){
            return new WinwinScheduleLogService();
        });
    }

    public function provides()
    {
        return ['WLog'];
    }

}