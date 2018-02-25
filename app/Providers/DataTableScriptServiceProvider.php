<?php

namespace App\Providers;

use App\Services\DataTableScriptService;
use Illuminate\Support\ServiceProvider;

class DataTableScriptServiceProvider extends ServiceProvider
{

    //protected $defer = true;
    /**
     * Bootstrap the application services.
     *
     * @return void
     */

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton('dataTableScript',function($app){

            return new DataTableScriptService();

        });
    }
//
//    public function provides()
//    {
//        return ['dataTableScript'];
//    }
}
