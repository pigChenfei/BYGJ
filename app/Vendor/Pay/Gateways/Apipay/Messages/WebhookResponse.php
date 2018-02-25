<?php
namespace App\Vendor\Pay\Gateways\Apipay\Messages;

use App\Vendor\Pay\Gateways\Common\Messages\AbstractWebhookResponse;

class WebhookResponse extends AbstractWebhookResponse
{
    use GenerateSignature;

    public function isSuccessful()
    {
        // return $this->data\
    }
}