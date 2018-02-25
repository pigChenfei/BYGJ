<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 17/3/9
 * Time: 上午11:45
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\WinwinAuthService;


class WinwinAuthServiceProvider extends ServiceProvider
{

    public function register()
    {
        //
        $this->app->singleton('winwinAuth',function($app){
            return new WinwinAuthService();
        });
    }

}