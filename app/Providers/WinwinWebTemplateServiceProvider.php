<?php
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/28
 * Time: 下午9:57
 */

namespace App\Providers;
use App\Models\Carrier;
use App\Services\WinwinWebTemplateService;
use Illuminate\Support\ServiceProvider;

class WinwinWebTemplateServiceProvider extends ServiceProvider
{


    protected $defer = true;


    public function register()
    {
        $this->app->singleton('WTemplate',function($app) {
            try{
                return new WinwinWebTemplateService(\WinwinAuth::currentWebCarrier());
            }catch (\Exception $e){
                //TODO 仅仅测试环境为了生成ide-helper使用,否则生成不了helper. 生产环境上线之前必须要去掉这行代码.
                if(\App::environment() != 'production'){
                    $carrier = Carrier::first();
                    return new WinwinWebTemplateService($carrier);
                }
            }
        });
    }

    public function provides()
    {
        return ['WTemplate'];
    }

}