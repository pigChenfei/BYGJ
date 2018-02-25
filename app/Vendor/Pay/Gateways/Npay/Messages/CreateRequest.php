<?php
namespace App\Vendor\Pay\Gateways\Npay\Messages;

use App\Vendor\Pay\Gateways\Common\Messages\AbstractRequest;
use App\Vendor\Pay\Gateways\Common\Messages\RequestMethod;
use Carbon\Carbon;
use App\Models\CarrierPayChannel;

class CreateRequest extends AbstractRequest
{
    use GenerateSignature;

    public $method = RequestMethod::FORM_POST;

    public $is_redirect = true;

    public $parameters = [
        'merchantId',
        'merOrderId',
        'txnAmt', // 交易金额
        'backUrl',
        'frontUrl',
        'bankId', //
        'subject', // 上送报文时需BASE64编码，参与签名计算时不需要编码
        'body', // 上送报文时需BASE64编码，参与签名计算时不需要编码
        'userId', // 商户用户系统中的Id 可选项
        'merResv1', // 保留字段，支持json字符串
        'gateway',
        'dcType' // 当gateway取值bank时必须，取值： 0：借记卡
    ];

    public function transTime()
    {
        return Carbon::now()->format('Ymdhis');
    }

    public function transIp()
    {
        return $_SERVER["REMOTE_ADDR"];
    }

    public function dcType()
    {
        if ($this->model->pay_type === 'bank') {
            return 0;
        }
        return '';
    }

    public function sendData($params)
    {
        $payChannel = $this->model->carrierPayChannel;
        if (empty($payChannel)) {
            throw new \Exception('运营商支付渠道未设置，请联系运营商', 40001);
        }
        $params['txnAmt'] = $params['txnAmt'] * 100; // Npay 接口单位为分。
        $params['subject'] = base64_encode($params['subject']);
        $params['body'] = base64_encode($params['body']);
        $paramsNeedSign = $this->base64Npay($params);
        $params['signMethod'] = 'MD5';
        $params['signature'] = $this->signNpay($paramsNeedSign, $payChannel->bindedThirdPartGateway->private_key);
        \Log::info([
            '订单号' . $params['merOrderId'] . 'signature' => $params['signature']
        ]);
        return parent::sendData($params);
    }
}