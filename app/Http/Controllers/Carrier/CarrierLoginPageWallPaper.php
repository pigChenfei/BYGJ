<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 2017/5/8
 * Time: 下午9:02
 */

namespace App\Http\Controllers\Carrier;


class CarrierLoginPageWallPaper
{

    public function index(){
        return \App\Services\BeingWallPaper::getPaper();
    }

}