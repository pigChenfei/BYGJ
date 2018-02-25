<?php
namespace App\Vendor\Pay\Gateways\Npay\Messages;

use App\Vendor\Pay\Gateways\Common\Messages\AbstractRedirectResponse;
use App\Vendor\Pay\Gateways\Common\Messages\RedirectMethod;

class CreateResponse extends AbstractRedirectResponse
{

    public function getRedirectUrl()
    {
        return $this->getRequest()->getURL();
    }

    public function getRedirectMethod()
    {
        return RedirectMethod::POST;
    }

    public function response($data)
    {
        $reqData = $this->getRedirectData();
        $platform = strpos($reqData['gateway'], 'pay') !== false ? config('banklist.plat')[$reqData['gateway']] : '';
        if ($data instanceof \stdClass) {
            if ($data->success == 1) {
                return [
                    'amount' => $data->txnAmt,
                    'success' => 200,
                    'signature' => $data->signature,
                    'qrcode' => isset($data->payLink) ? $data->payLink : '',
                    'prepayUrl' => isset($data->prepayUrl) ? $data->prepayUrl : '',
                    'platform' => $platform,
                    'message' => $data->msg
                ];
            } else {
                return [
                    'success' => 4000,
                    'signature' => isset($data->signature) ? $data->signature : '',
                    'message' => $platform ? '请求' . $platform . '平台出错，请联系管理员' : $data->msg
                ];
            }
        }
        return $data;
    }
}