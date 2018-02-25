<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/1
 * Time: 21:30
 */
namespace App\Vendor\Pay\Gateways\Guofubao\Messages;

use App\Vendor\Pay\Gateways\Common\Messages\AbstractWebhookRequest;

class WebhookRequest extends AbstractWebhookRequest
{
    use GenerateSignature;

    public function handle()
    {
        $input = $this->getRequestData();
        if (empty($this->payment)) {
            \Log::info('订单不存在');
            return;
        }
        $params = array(
            'version' => $input['version'],
            'tranCode' => $input['tranCode'],
            'merchantID' => $this->payment->carrierPayChannel->bindedThirdPartGateway->merchant_number,
            'merOrderNum' => $this->payment->pay_order_number,
            'tranAmt' => $this->payment->amount,
            'feeAmt' => $input['feeAmt'],
            'frontMerUrl' => '',
            'backgroundMerUrl' => '',
            'tranDateTime' => $input['tranDateTime'],
            'orderId' => $input['orderId'],
            'gopayOutOrderId' => $input['gopayOutOrderId'],
            'tranIP' => $input['tranIP'],
            'respCode' => $input['respCode'],
            'gopayServerTime' => $this->payment->created_at->format('YmdHis'),
            'VerficationCode' => $this->payment->carrierPayChannel->bindedThirdPartGateway->merchant_identify_code
        );
        $signature = $this->generateSignature($params);
        if ($signature == $input['signValue']) {
            if ($input['respCode'] == '0000') {
                $response = parent::handle();
                \Log::info('========= ========    gopay 通知处理成功   ========= ========');
                return $response;
            } else {
                \Log::error('========= ========    gopay 通知处理失败    ========= ========');
            }
        } else {
            \Log::error('========= ========    gopay 通知处理失败    ========= ========');
        }
    }
}