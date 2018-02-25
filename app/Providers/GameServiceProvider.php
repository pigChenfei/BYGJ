<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Vendor\Game\Imp\GameBase;
use App\Vendor\Game\BBin;
use App\Vendor\Game\VR;
use App\Vendor\Game\OneWorks;
use App\Vendor\Game\PT;
use App\Vendor\Game\Sunbet;
use App\Vendor\Game\MG;

class GameServiceProvider extends ServiceProvider
{

    public function register()
    {
        //
        $this->app->singleton('GameService', function ($app, $module) {
            return new GameBase($app->make($module[0]));
        });
        
        $this->app->bind('BBIN', function ($app) {
            return new BBin();
        });
        
        $this->app->bind('VR', function ($app) {
            return new VR();
        });
        
        $this->app->bind('ONWORKS', function ($app) {
            return new OneWorks();
        });
        
        $this->app->bind('PT', function ($app) {
            return new PT();
        });
        
        $this->app->bind('SUNBET', function ($app) {
            return new Sunbet();
        });
        
        $this->app->bind('AG', function ($app) {
            return new Sunbet();
        });
        
        $this->app->bind('MG', function ($app) {
            return new MG();
        });
    }

    public function provides()
    {
        return [
            'GameService'
        ];
    }
}

