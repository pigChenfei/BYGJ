<?php
namespace App\Providers;

use App\Models\CarrierPlayerLevel;
use App\Models\Log\PlayerBetFlowLog;
use App\Observers\CarrierPlayerLevelObserver;
use App\Observers\PlayerBetFlowObserver;
use Illuminate\Support\ServiceProvider;
use App\Models\Log\PlayerDepositPayLog;
use App\Observers\PlayerDepositPayObserver;

/**
 * 观察者服务提供者
 */
class ModelObserverProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        CarrierPlayerLevel::observe(CarrierPlayerLevelObserver::class);
        PlayerDepositPayLog::observe(PlayerDepositPayObserver::class);
        PlayerBetFlowLog::observe(PlayerBetFlowObserver::class);
    }

    public function register()
    {
        // code...
    }
}
