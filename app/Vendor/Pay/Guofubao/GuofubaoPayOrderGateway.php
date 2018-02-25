<?php
namespace App\Vendor\Pay\Guofubao;

use App\Helpers\IP\RealIpHelper;
use App\Models\CarrierActivity;
use App\Models\CarrierPayChannel;
use App\Models\Def\PayChannel;
use App\Models\Log\PlayerDepositPayLog;
use App\Models\Player;
use App\Models\PlayerBankCard;
use App\Vendor\Pay\Exception\PayOrderRuntimeException;
use App\Vendor\Pay\Gateway\PayBank;
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
class GuofubaoPayOrderGateway extends PayOrderAbstract
{

    public $orderBankCode;

    /**
     *
     * @param Player $player            
     * @param CarrierPayChannel $payChannel            
     * @return PayOrderFetchResponse
     */
    public function createOrder(Player $player, CarrierPayChannel $payChannel, $amount, PlayerBankCard $playerBankCard = null, $depositTime = null, $depositType = null, CarrierActivity $carrierActivity = null)
    {
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
        // 此处中断，后续接上。。
        $builder = new GuofubaoOrderBuilder($payChannel, $order, $amount);
        if ($this->orderBankCode) {
            $builder->addParameter('bankCode', $this->orderBankCode);
        }
        $this->orderFetchResponse = $builder->fetchOrderResult();
        return $this->orderFetchResponse;
    }

    /**
     * 检测订单是否合法
     * 
     * @param Request $request            
     * @return \Response
     */
    public function verifyOrderIsLegal(Request $request)
    {
        $order_no = $request->get('pay_order_number');
        if (! $order_no) {
            throw new PayOrderRuntimeException('缺少订单号参数');
        }
        $playerDepositPay = PlayerDepositPayLog::retrieveByOrderNumber($order_no)->first();
        if (empty($playerDepositPay)) {
            throw new PayOrderRuntimeException('订单号不存在');
        }
        // 判断是否已经支付成功过了,如果支付成功过了,则不需要再次验证,
        if ($playerDepositPay->isPayedSuccessfully()) {
            return \Response::make('SUCCESS');
        }
        // 验证请求是否来自合法数据;
        $builder = new GuofubaoOrderBuilder($playerDepositPay->payChannel, $playerDepositPay, $playerDepositPay->amount);
        try {
            $builder->verifyNotifyDataIsLegal($request);
            $this->playerDepositPayOrder = $playerDepositPay;
            return \Response::make('SUCCESS');
        } catch (PayOrderRuntimeException $e) {
            throw $e;
        }
    }

    public function getBankList()
    {
        return [
            new PayBank('CCB', '中国建设银行', '/app/img/bank_icon/CCB.jpg'),
            new PayBank('CMB', '招商银行', '/app/img/bank_icon/CMB.jpg'),
            new PayBank('ICBC', '中国工商银行', '/app/img/bank_icon/ICBC.jpg'),
            new PayBank('BOC', '中国银行', '/app/img/bank_icon/BOC.jpg'),
            new PayBank('ABC', '中国农业银行', '/app/img/bank_icon/ABC.jpg'),
            new PayBank('BOCOM', '交通银行', '/app/img/bank_icon/BOCOM.jpg'),
            new PayBank('CMBC', '中国民生银行', '/app/img/bank_icon/CMBC.jpg'),
            new PayBank('HXBC', '华夏银行', '/app/img/bank_icon/HXBC.jpg'),
            new PayBank('CIB', '兴业银行', '/app/img/bank_icon/CIB.jpg'),
            new PayBank('SPDB', '上海浦东发展银行', '/app/img/bank_icon/SPDB.jpg'),
            new PayBank('GDB', '广东发展银行', '/app/img/bank_icon/GDB.jpg'),
            new PayBank('CITIC', '中信银行', '/app/img/bank_icon/CITIC.jpg'),
            new PayBank('CEB', '光大银行', '/app/img/bank_icon/CEB.jpg'),
            new PayBank('PSBC', '中国邮政储存银行', '/app/img/bank_icon/PSBC.jpg'),
            new PayBank('BOBJ', '北京银行', '/app/img/bank_icon/BOBJ.jpg'),
            new PayBank('TCCB', '天津银行', '/app/img/bank_icon/TCCB.jpg'),
            new PayBank('BOS', '上海银行', '/app/img/bank_icon/BOS.jpg'),
            new PayBank('PAB', '平安银行', '/app/img/bank_icon/PAB.jpg'),
            new PayBank('NBCB', '宁波银行', '/app/img/bank_icon/NBCB.jpg'),
            new PayBank('NJCB', '南京银行', '/app/img/bank_icon/NJCB.jpg')
        ];
    }
}