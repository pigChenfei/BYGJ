<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 2017/4/5
 * Time: 下午2:52
 */

namespace App\Facades;
use Illuminate\Support\Facades\Facade;

class WinwinScheduleLogFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'WLog';
    }

}