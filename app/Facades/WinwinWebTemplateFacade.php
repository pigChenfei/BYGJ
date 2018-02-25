<?php
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/28
 * Time: 下午9:58
 */

namespace App\Facades;
use App\Services\WinwinWebTemplateService;
use Illuminate\Support\Facades\Facade;

class WinwinWebTemplateFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'WTemplate';
    }

}