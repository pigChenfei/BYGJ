<?php
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/20
 * Time: 下午7:18
 */

namespace App\Http\Controllers\Web;


use App\Helpers\IP\RealIpHelper;
use App\Vendor\Pay\Gateway\PayNotifyRunTime;
use Illuminate\Http\Request;

class PayNotifyController
{

    public function index(Request $request){
        \Debugbar::disable();
        $runTime = new PayNotifyRunTime($request);
        \Log::write('error',"==========获取到支付回调信息==========\n",[
                'ip'   => RealIpHelper::getIp(),
                'host' => $request->getHost(),
                'data' => $request->all(),
            ]
        );
        try{
            \Log::error("==========支付回调验证成功==========\n");
            return $runTime->handleNotifyRequest();
        }catch (\Exception $e){
            \Log::write('error',"==========支付回调验证失败==========\n",[
                    'message'   => $e->getMessage(),
                ]
            );
            return \Response::make($e->getMessage());
        }
    }

}