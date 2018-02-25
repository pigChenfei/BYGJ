<?php

namespace App\Vendor\Pay\Zhifu;
use App\Helpers\IP\RealIpHelper;
use App\Models\CarrierPayChannel;
use App\Models\Def\PayChannel;
use App\Models\Log\PlayerDepositPayLog;
use App\Models\Player;
use App\Models\PlayerBankCard;
use App\Vendor\Pay\Exception\PayOrderRuntimeException;
use App\Vendor\Pay\Gateway\PayOrderAbstract;
use App\Vendor\Pay\Gateway\PayOrderFetchResponse;
use App\Vendor\Pay\Gateway\PayOrderInterface;
use Illuminate\Http\Request;

/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/20
 * Time: 下午4:25
 */
class ZhifuPayOrderGateway extends PayOrderAbstract
{

    
    /**
     * @param Player $player
     * @param CarrierPayChannel $payChannel
     * @return PayOrderFetchResponse
     */
    public function createOrder(Player $player, CarrierPayChannel $payChannel, $amount ,PlayerBankCard $playerBankCard = null, $depositTime = null, $depositType = null)
    {
        if($this->orderFetchResponse){
            return $this->orderFetchResponse;
        }
        if($payChannel->payChannel->payChannelType->isThirdPartPay() == false){
            throw new PayOrderRuntimeException('不是有效的三方存款平台');
        }
        $order = new PlayerDepositPayLog();
        $order->ip = RealIpHelper::getIp();
        $order->carrier_id = $player->carrier_id;
        $order->player_id  = $player->player_id;
        $builder = new ZhifuOrderBuilder($payChannel,$order,$amount);
        $this->orderFetchResponse = $builder->fetchOrderResult();
        return $this->orderFetchResponse;
    }

    /**
     * 检测订单是否合法
     * @param Request $request
     * @return \Response
     */
    public function verifyOrderIsLegal(Request $request)
    {
        $order_no = $request->get('order_no');
        if(!$order_no){
            throw new PayOrderRuntimeException('缺少订单号参数');
        }
        $playerDepositPay = PlayerDepositPayLog::retrieveByOrderNumber($order_no)->first();
        if(empty($playerDepositPay)){
            throw new PayOrderRuntimeException('订单号不存在');
        }
        //判断是否已经支付成功过了,如果支付成功过了,则不需要再次验证,
        if($playerDepositPay->isPayedSuccessfully()){
            return \Response::make('SUCCESS');
        }
        //验证请求是否来自合法数据;
        $builder = new ZhifuOrderBuilder($playerDepositPay->payChannel,$playerDepositPay,$playerDepositPay->amount);
        try{
            $builder->verifyNotifyDataIsLegal($request);
            $this->playerDepositPayOrder = $playerDepositPay;
            return \Response::make('SUCCESS');
        }catch (PayOrderRuntimeException $e){
            throw $e;
        }
    }

    public function getBankList()
    {
        return [

        ];
    }

}