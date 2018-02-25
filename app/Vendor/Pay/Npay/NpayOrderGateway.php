<?php
namespace Vendor\Pay\Npay;

use App\Vendor\Pay\Gateway\PayOrderAbstract;
use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\CarrierPayChannel;
use App\Models\PlayerBankCard;
use App\Models\CarrierActivity;
use App\Vendor\Pay\Gateway\PayBank;
use App\Vendor\Pay\Exception\PayOrderRuntimeException;
use App\Models\Log\PlayerDepositPayLog;
use App\Helpers\IP\RealIpHelper;

class NpayOrderGateway extends PayOrderAbstract
{

    public function createOrder(Player $player, CarrierPayChannel $payChannel, $data, PlayerBankCard $playerBankCard = null, $depositTime = null, $depositType = null, CarrierActivity $carrierActivity = null)
    {
        $amount = is_array($data) ? $data['amount'] : $data;
        if ($this->orderFetchResponse) {
            return $this->orderFetchResponse;
        }
        if ($payChannel->payChannel->payChannelType->isThirdPartPay() == false) {
            throw new PayOrderRuntimeException('不是有效的三方存款平台');
        }
        $order = new PlayerDepositPayLog();
        $order->ip = RealIpHelper::getIp();
        $order->carrier_id = $player->carrier_id;
        $order->player_id = $player->player_id;
        $order->carrier_pay_channel = $payChannel->id;
        $order->amount = $amount;
        $order->bank_no = is_array($data) ? $data['bank_no'] : '';
        $order->pay_type = is_array($data) ? $data['pay_type'] : '';
        $builder = new NpayOrderBuilder($payChannel, $order, $amount);
        if ($this->orderBankCode) {
            $builder->setParams('bankId', $this->orderBankCode);
        }
        $this->orderFetchResponse = $builder->fetchOrderResult();
        return $this->orderFetchResponse;
    }

    public function verifyOrderIsLegal(Request $request)
    {}

    public function getBankList()
    {
        $banklists = config('banklist.NPAY');
        $list = array();
        foreach ($banklists as $key => $val) {
            $list[] = new PayBank($key, $val, '');
        }
        return $list;
    }
}

