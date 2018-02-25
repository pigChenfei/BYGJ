<?php
namespace App\Vendor\Pay\Gateways\Common\Messages;

use App\Models\Payment;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use RuntimeException;

abstract class AbstractWebhookRequest extends AbstractNotifyRequest
{

    public $updateable = [
        'status',
        'gateway_transaction_id',
        'pay_order_channel_trade_number',
        'operate_time'
    ];

    public $validatable = [
        'ip',
        'signature',
        'amount'
    ];

    protected $ip_whitelist = null;

    protected $validate_amount_in_cents = false;

    protected $payment = null;

    protected $payment_id;

    protected $amount_paid = 0;

    protected $paid_partial = false;

    protected $payment_status = null;

    // RespCode=00&acqID=99020344&charSet=UTF-8&merID=800039289992002&orderAmount=28.00&orderCurrency=CNY&orderNum=H521003440775126&paymentSchema=TP&settAmount=28&settCurrency=CNY&signType=MD5&signature=d81ac1d607dc85f929c2009c82986bbc&transID=dI1KJMJxNsKixRLb&transTime=20170521205904&transType=PURC&version=VER000000002
    protected function isRepeatWebhook($data)
    {
        if (Cache::has($this->fingerprint($data))) {
            return true;
        }
        
        Cache::put($this->fingerprint($data), $data, 10);
        return false;
    }

    /**
     * Get a unique fingerprint for the webhook request.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected function fingerprint($data)
    {
        $route = request()->route();
        
        return sha1(implode('|', array_merge($data, [
            $route->domain(),
            $route->uri(),
            request()->ip()
        ])));
    }

    protected function validateIp()
    {
        $valid = true;
        if (count($this->ip_whitelist) > 0) {
            $valid = in_array(request()->ip(), $this->ip_whitelist);
        }
        
        return $valid;
    }

    protected function validateAmount()
    {
        $currency_paid_gateway = $this->getValue('currency');
        $amount_paid_gateway = $this->getValue('amount');
        Log::info("currency = $currency_paid_gateway , amount=$amount_paid_gateway");
        if ($this->validate_amount_in_cents) {
            $amount_paid_gateway *= 100;
        }
        
        if ($this->payment->currency_local) {
            if ($currency_paid_gateway == $this->payment->currency_local) {
                if ($this->payment->amount_local - $amount_paid_gateway < 1) {
                    $this->amount_paid = $this->payment->amount;
                    return true;
                } else {
                    // partial payments
                    $this->paid_partial = true;
                    $this->amount_paid = $amount_paid_gateway / $this->payment->amount_local * $this->payment->amount;
                }
            } else {}
        } else {
            if ($currency_paid_gateway == $this->payment->currency) {
                if ($this->payment->amount - $amount_paid_gateway < 1) {
                    $this->amount_paid = $this->payment->amount;
                    return true;
                } else {
                    // partial payments
                    $this->paid_partial = true;
                    $this->amount_paid = $amount_paid_gateway;
                }
            }
        }
        
        return false;
    }

    public function validateSignature()
    {
        return true;
    }
}