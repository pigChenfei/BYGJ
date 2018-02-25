<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 17/3/1
 * Time: 下午1:25
 */

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class DataTableScriptFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'dataTableScript';
    }

}