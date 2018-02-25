<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 2017/5/8
 * Time: 下午9:03
 */

namespace App\Http\Controllers\Agent;


class AgentLoginPageWallPaper
{

    public function index(){
        return \App\Services\BeingWallPaper::getPaper();
    }

}