<?php
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/20
 * Time: 下午5:26
 */

namespace App\Vendor\Pay\Zhifu;


use App\Helpers\IP\RealIpHelper;
use App\Models\CarrierPayChannel;
use App\Models\Log\PlayerDepositPayLog;
use App\Vendor\Pay\Exception\PayOrderRuntimeException;
use App\Vendor\Pay\Gateway\PayOrderFetchResponse;
use App\Vendor\Pay\Gateway\PayOrderRuntime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;

class ZhifuOrderBuilder
{

    const ORDER_CREATE_URL = 'https://api.dinpay.com/gateway/api/scanpay';
    /**
     * @var CarrierPayChannel
     */
    private $carrierPayChannel;

    /**
     * @var PlayerDepositPayLog
     */
    public $payOrder;

    private $primaryKey;

    /**
     * 金额
     * @var
     */
    private $amount;

    private $params = [];

    /**
     * ZhifuOrderBuilder constructor.
     * @param ZhifuPayOrderGateway $gateway
     * @param PlayerDepositPayLog $payLog
     */
    public function __construct(CarrierPayChannel $payChannel, PlayerDepositPayLog &$payLog, $amount)
    {
        if (!$payChannel->bindedThirdPartGateway) {
            throw new PayOrderRuntimeException('无法找到第三方支付网关配置!');
        }
        if (!$payChannel->bindedThirdPartGateway->private_key) {
            throw new PayOrderRuntimeException('没有配置三方支付网关私钥,请检查!');
        }
        if (!$payLog->carrier_id) {
            throw new PayOrderRuntimeException('该订单没有所属运营商,请检查!');
        }
        $this->carrierPayChannel = $payChannel;
        $this->payOrder = $payLog;
        $this->amount = $amount;
        $this->primaryKey = openssl_get_privatekey($this->carrierPayChannel->bindedThirdPartGateway->private_key);
    }

    public function buildParam()
    {
        $this->payOrder->pay_order_number = PlayerDepositPayLog::generatePayNumber();
        $this->payOrder->amount = $this->amount;
        $this->payOrder->carrier_pay_channel = $this->carrierPayChannel->id;
        $this->payOrder->save();
        $this->params = [
            'client_ip' => RealIpHelper::getIp(),
            'interface_version' => 'V3.1',
            'merchant_code' => $this->carrierPayChannel->bindedThirdPartGateway->merchant_number,
            'notify_url' => 'http://' . $this->carrierPayChannel->bindedThirdPartGateway->merchant_bind_domain . '/' . PayOrderRuntime::orderNotifyRouteName(),
            'order_amount' => $this->amount,
            'order_no' => $this->payOrder->pay_order_number,
            'order_time' => $this->payOrder->created_at->toDateTimeString(),
            'product_code' => '001',
            'product_desc' => 'ZhiFu Pay Order',
            'product_name' => 'ZhiFu Pay Order',
            'product_num' => 1,
            'service_type' => 'weixin_scan',
        ];
    }

    private function signParam()
    {
        $signArray = array_map(function ($key) {
            return $key . '=' . $this->params[$key];
        }, array_keys($this->params));
        $signStr = implode('&', $signArray);
        //dd($signStr);
        openssl_sign($signStr, $signInfo, $this->primaryKey, OPENSSL_ALGO_MD5);
        $signInfo = base64_encode($signInfo);
        $this->params['sign'] = $signInfo;
        $this->params['extend_param'] = '';
        $this->params['extra_return_param'] = '';
        $this->params['sign_type'] = 'RSA-S';
    }


    /**
     * @return PayOrderFetchResponse
     */
    public function fetchOrderResult()
    {
        $this->buildParam();
        \WLog::info('==========智付请求数据==========',['data' => $this->params]);
        $this->signParam();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::ORDER_CREATE_URL);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        if ($response) {
            $result = json_decode(json_encode(simplexml_load_string($response)), true);
            if(!isset($result['response'])){
                throw new PayOrderRuntimeException('智付网关出错,错误数据:'.json_encode($result));
            }
            if(isset($result['response']['resp_code']) && $result['response']['resp_code'] == 'FAIL'){
                throw new PayOrderRuntimeException('智付网关出错,错误数据:'.$result['response']['result_desc']);
            }
            if(!isset($result['response']['qrcode'])){
                throw new PayOrderRuntimeException('智付网关数据解析错误,信息:'.$result['response']['result_desc']);
            }
            $response = new PayOrderFetchResponse();
            $response->payOrder = $this->payOrder;
            $response->payUrl = $result['response']['qrcode'];
            $response->payType = PayOrderFetchResponse::WEB_PAY_TYPE_WECHAT_SCAN;
            return $response;
        } else {
            throw new PayOrderRuntimeException('智付网关出错,错误数据:' . json_encode(simplexml_load_string($response)));
        }
    }

    public function verifyNotifyDataIsLegal(Request $request)
    {
        $this->params = [];
        if($request->get('bank_seq_no')){
            $this->params['bank_seq_no'] = $request->get('bank_seq_no');
        }
        if($request->get('extra_return_param')){
            $this->params['extra_return_param'] = $request->get('extra_return_param');
        }
        $this->params = array_merge($this->params,[
            'interface_version' => $request->get('interface_version'),
            'merchant_code' => $request->get('merchant_code'),
            'notify_id' => $request->get('notify_id'),
            'notify_type' => $request->get('notify_type'),
            'order_amount' => $request->get('order_amount'),
            'order_no' => $request->get('order_no'),
            'order_time' => $request->get('order_time'),
            'trade_no' => $request->get('trade_no'),
            'trade_status' => $request->get('trade_status'),
            'trade_time' => $request->get('trade_time')
        ]);

        $signArray = array_map(function ($key) {
            return $key . '=' . $this->params[$key];
        }, array_keys($this->params));
        $signStr = implode('&', $signArray);
        $publicKey = openssl_get_publickey($this->carrierPayChannel->bindedThirdPartGateway->public_key);
        $flag = openssl_verify(
            $signStr,
            base64_decode($request->get('sign')),
            $publicKey,
            OPENSSL_ALGO_MD5
        );
        if($flag){
            $this->payOrder->pay_order_channel_trade_number = $request->get('trade_no');
            \WLog::info('==========支付回调验证成功==========');
            return true;
        }
        throw new PayOrderRuntimeException('智付订单验证失败,非法订单数据.');
    }

}