<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\AppBaseController;
use App\Models\CarrierPayChannel;
use App\Models\Player;
use App\Vendor\Pay\Gateway\PayOrderRuntime;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/11
 * Time: 10:15
 */
class TestPayGatewayController extends AppBaseController
{


    public function playerOrder(){

        $player = Player::findOrFail(1);

        $channel = CarrierPayChannel::findOrFail(8);

        $orderRuntime = new PayOrderRuntime($player,$channel,10);

        dd($orderRuntime->createOrder());

    }

}