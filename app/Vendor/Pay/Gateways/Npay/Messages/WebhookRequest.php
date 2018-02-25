<?php
namespace App\Vendor\Pay\Gateways\Npay\Messages;

use App\Vendor\Pay\Gateways\Common\Messages\AbstractWebhookRequest;
use App\Models\Log\PlayerDepositPayLog;

class WebhookRequest extends AbstractWebhookRequest
{
    use GenerateSignature;

    public $statusCode = 1001;

    public $statusKey = 'respCode';

    public function handle()
    {
        $input = $this->getRequestData();
        if (empty($this->payment)) {
            \Log::info('订单不存在');
            return;
        }
        $params = array(
            'merchantId' => $this->payment->carrierPayChannel->bindedThirdPartGateway->merchant_number,
            'merOrderId' => $this->payment->pay_order_number,
            'txnAmt' => $this->payment->amount * 100,
            'respCode' => $input['respCode'],
            'respMsg' => $input['respMsg']
        );
        // $params = [
        // 'merchantId' => $this->payment->carrierPayChannel->bindedThirdPartGateway->merchant_number,
        // 'merOrderId' => $this->payment->pay_order_number,
        // 'txnAmt' => $this->payment->amount * 100, // 交易金额
        // 'backUrl' => 'http://' . $this->payment->carrier->site_url . "/postback/npay/" . $this->payment->pay_order_number,
        // 'frontUrl' => 'http://' . $this->payment->carrier->site_url . "/player.pay/return/npay/" . $this->payment->pay_order_number,
        // 'bankId' => $this->payment->bank_no, //
        // 'subject' => base64_encode(config('gateway.npay.subject')), // 上送报文时需BASE64编码，参与签名计算时不需要编码
        // 'body' => base64_encode(config('gateway.npay.body')), // 上送报文时需BASE64编码，参与签名计算时不需要编码
        // 'userId' => '', // 商户用户系统中的Id 可选项
        // 'merResv1' => '', // 保留字段，支持json字符串
        // 'gateway' => $this->payment->pay_type,
        // 'dcType' => $this->payment->pay_type === 'bank' ? 0 : '' // 当gateway取值bank时必须，取值： 0：借记卡
        // ];
        $paramsNeedSign = $this->base64Npay($params);
        $signature = $this->signNpay($paramsNeedSign, $this->payment->carrierPayChannel->bindedThirdPartGateway->private_key);
        if (array_key_exists('signature', $input) && array_get($input, 'signature') === $signature) {
            if (array_key_exists('txnAmt', $input) && array_get($input, 'txnAmt') == $params['txnAmt']) {
                $response = parent::handle();
                \Log::info('========= ========    npay 通知处理成功   ========= ========');
                return $response;
            } else {
                \Log::error('========= ========    npay 通知金额错误    ========= ========');
            }
        } else {
            \Log::error('========= ========       npay 通知签名错误     ========= ========');
        }
    }
}