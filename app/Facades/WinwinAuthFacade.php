<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 17/3/9
 * Time: 上午11:45
 */

namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class WinwinAuthFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'winwinAuth';
    }

}