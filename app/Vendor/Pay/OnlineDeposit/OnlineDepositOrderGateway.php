<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2017/12/27
 * Time: 22:23
 */
namespace App\Vendor\Pay\OnlineDeposit;

use App\Helpers\IP\RealIpHelper;
use App\Models\CarrierActivity;
use App\Models\CarrierPayChannel;
use App\Models\Log\PlayerDepositPayLog;
use App\Models\Player;
use App\Models\PlayerBankCard;
use App\Vendor\Pay\Gateway\PayOrderAbstract;
use App\Vendor\Pay\Gateway\PayOrderFetchResponse;
use Illuminate\Http\Request;

class OnlineDepositOrderGateway extends PayOrderAbstract
{

    public function createOrder(Player $player, CarrierPayChannel $payChannel, $data,
        PlayerBankCard $playerBankCard = null, $depositTime = null, $depositType = null, CarrierActivity $carrierActivity = null,
        $credential = null)
    {
        $amount = is_array($data) ? $data['amount'] : $data;
        parent::createOrder($player, $payChannel, $amount, $playerBankCard, $depositTime, $depositType,
            $carrierActivity);
        
        $order = new PlayerDepositPayLog();
        $order->ip = RealIpHelper::getIp();
        $order->carrier_id = $player->carrier_id;
        $order->player_id = $player->player_id;
        $order->carrier_pay_channel = $payChannel->id;
        $order->amount = $amount;
        $order->bank_no = is_array($data) ? $data['bank_no'] : '';
        $order->pay_type = is_array($data) ? $data['pay_type'] : '';
        $order->pay_order_number = PlayerDepositPayLog::generatePayNumber();
        $order->carrier_activity_id = $carrierActivity ? $carrierActivity->id : null;
        $order->credential = '';
        $order->status = PlayerDepositPayLog::ORDER_STATUS_CREATED;
        $order->save();
        
        $response = new PayOrderFetchResponse();
        $response->payOrder = $order;
        $response->payType = PayOrderFetchResponse::WEB_PAY_TYPE_REDIRECT;
        return $response;
    }

    /**
     * 检测订单是否合法
     *
     * @param Request $request
     * @return \Response
     */
    public function verifyOrderIsLegal(Request $request)
    {}

    public function getBankList()
    {
        // TODO: 从数据库查线下转账的银行卡列表
    }
}