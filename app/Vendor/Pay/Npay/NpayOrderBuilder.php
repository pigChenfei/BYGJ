<?php
namespace Vendor\Pay\Npay;

use App\Models\CarrierPayChannel;
use App\Vendor\Pay\Exception\PayOrderRuntimeException;
use App\Models\Log\PlayerDepositPayLog;
use App\Vendor\Pay\Gateway\PayOrderRuntime;

class NpayOrderBuilder
{

    const URL = 'http://epay-testing.nongfupay.com/pay';

    /**
     *
     * @var CarrierPayChannel
     */
    private $carrierPayChannel;

    /**
     *
     * @var PlayerDepositPayLog
     */
    public $payOrder;

    private $primaryKey;

    /**
     * 金额
     *
     * @var
     *
     */
    private $amount;

    private $params = [];

    /**
     *
     * @param
     *
     * @param PlayerDepositPayLog $payLog
     */
    public function __construct(CarrierPayChannel $payChannel, PlayerDepositPayLog &$payLog, $amount)
    {
        if (! $payChannel->bindedThirdPartGateway) {
            throw new PayOrderRuntimeException('无法找到第三方支付网关配置!');
        }
        if (! $payChannel->bindedThirdPartGateway->merchant_identify_code) {
            throw new PayOrderRuntimeException('没有配置三方支付网关商户识别码,请检查!');
        }
        if (! $payLog->carrier_id) {
            throw new PayOrderRuntimeException('该订单没有所属运营商,请检查!');
        }
        $this->carrierPayChannel = $payChannel;
        $this->payOrder = $payLog;
        $this->amount = $amount;
    }

    private function signNpay($params, $key)
    {
        ksort($params);
        $uri = urldecode(http_build_query($params));
        $uri = $uri . $key;
        $result = base64_encode(md5($uri, TRUE));
        return $result;
    }

    private function base64Npay($params, $decode = true)
    {
        $need_base64_fields = [
            'subject',
            'body'
        ];
        foreach ($need_base64_fields as $k) {
            if (array_key_exists($k, $params)) {
                if ($decode) {
                    $params[$k] = base64_decode($params[$k]);
                } else {
                    $params[$k] = base64_encode($params[$k]);
                }
            }
        }
        return $params;
    }

    private function buildParam()
    {
        $params = array();
        $this->payOrder->pay_order_number = PlayerDepositPayLog::generatePayNumber();
        $this->payOrder->amount = $this->amount;
        $this->payOrder->carrier_pay_channel = $this->carrierPayChannel->id;
        $this->payOrder->save();
        $params = array(
            'merchantId' => $this->carrierPayChannel->bindedThirdPartGateway->merchant_number,
            'merOrderId' => $this->payOrder->pay_order_number,
            'txnAmt' => number_format($this->amount, 2, ".", ""), // 交易金额,
            'frontMerUrl' => '',
            'backgroundMerUrl' => 'http://' . $this->carrierPayChannel->bindedThirdPartGateway->merchant_bind_domain . '/' . PayOrderRuntime::orderNotifyRouteName(),
            'body' => 'Npay Order',
            'subject' => 'Npay Order',
            'gateway' => 'bank'
        );
        
        $key = $this->carrierPayChannel->bindedThirdPartGateway->private_key;
        // 支付方式
        $params['bankId'] = '01020000';
        $params['dcType'] = '0';
        
        // 加密
        $paramsNeedSign = $this->base64Npay($params, true);
        $signature = $this->signNpay($paramsNeedSign, $key);
        $params['signMethod'] = 'MD5';
        $params['signature'] = $signature;
        
        return $params;
    }

    /**
     *
     * @return the $primaryKey
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     *
     * @return the $amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     *
     * @return the $params
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     *
     * @param string $primaryKey
     */
    public function setPrimaryKey($primaryKey)
    {
        $this->primaryKey = $primaryKey;
    }

    /**
     *
     * @param field_type $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     *
     * @param multitype: $params
     */
    public function setParams($key, $val)
    {
        $this->params[$key] = $val;
    }
}

