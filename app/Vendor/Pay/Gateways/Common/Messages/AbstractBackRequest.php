<?php

namespace App\Vendor\Pay\Gateways\Common\Messages;

use Illuminate\Support\Facades\Log;

abstract class AbstractBackRequest extends AbstractNotifyRequest
{
    public $updateable_status = ['status'];
    protected $action = null;

    public function initialize($payment_id = null, $gateway)
    {
        Log::info('payment_id=', $payment_id);
        if (is_array($payment_id)) {
            $this->payment_id = $payment_id[0];
            $this->action = $payment_id[1];

            return parent::initialize($payment_id[0], $gateway);
        } else {
            Log::info('BackRequest initialize failed:' . $payment_id);
            return null;
        }
    }
}