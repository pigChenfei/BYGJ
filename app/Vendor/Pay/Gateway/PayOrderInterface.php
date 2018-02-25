<?php

namespace App\Vendor\Pay\Gateway;

use App\Models\CarrierActivity;
use App\Models\CarrierPayChannel;
use App\Models\Def\PayChannel;
use App\Models\Log\PlayerDepositPayLog;
use App\Models\Player;
use App\Models\PlayerBankCard;
use Illuminate\Http\Request;

/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/18
 * Time: 下午2:53
 */
interface PayOrderInterface
{

    /**
     * 创建订单
     * @param Player $player
     * @param CarrierPayChannel $payChannel
     * @param float $amount
     * @return PayOrderFetchResponse
     */
    public function createOrder(Player $player, CarrierPayChannel $payChannel, $amount, PlayerBankCard $playerBankCard = null,
                                $depositTime = null, $depositType = null, CarrierActivity $carrierActivity = null);


    /**
     * 添加自定义网关参数
     * @param \Closure|null $callable
     * @return mixed
     */
    public function applyCustomPayCondition(\Closure $callable = null);

    /**
     * 检测订单是否合法
     * @param Request $request
     * @return \Response
     */
    public function verifyOrderIsLegal(Request $request);


    /**
     * 订单完成后的会员账户处理逻辑
     * @return PlayerDepositPayLog
     */
    public function getDepositPayLogWhenVerifySuccess();


    /**
     * 获取支付平台支持的银行列表
     * @return PayBank[]
     */
    public function getBankList();
    
}