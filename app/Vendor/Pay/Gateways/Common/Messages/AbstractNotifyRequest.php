<?php
namespace App\Vendor\Pay\Gateways\Common\Messages;

use App\Models\Log\Base\BaseDepositModel;
use App\Models\Log\CarrierAgentDepositPayLog;
use App\Models\Log\PlayerDepositPayLog;
use App\Models\Payment;
use App\Payment\Utils;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Vendor\Pay\Tools\PaymentStatus;
use Carbon\Carbon;

abstract class AbstractNotifyRequest extends AbstractRequest
{

    public $updateable = [];

    public $validatable = [];

    protected $payment;

    protected $is_valid = null;

    protected $is_confirming = false;

    protected $payment_status = null;

    /**
     * 如果为固定回调地址的，则需要给此值赋值
     *
     * @var
     *
     */
    public $orderNumKey = null;

    public $data = null;

    public function __construct($payment_id = null, $gateway)
    {
        $this->initialize($payment_id, $gateway);
    }

    public function initialize($pay_order_number = null, $gateway)
    {
        if (empty($pay_order_number) && ! empty($this->orderNumKey)) {
            $pay_order_number = $this->getRequestData()[$this->orderNumKey];
        }
        $this->pay_order_number = $pay_order_number;
        if (! empty($pay_order_number)) {
            if (BaseDepositModel::isPlayerDepositOrder($pay_order_number)) {
                $this->payment = PlayerDepositPayLog::where('pay_order_number', $this->pay_order_number)->first();
            } else {
                $this->payment = CarrierAgentDepositPayLog::where('pay_order_number', $this->pay_order_number)->first();
            }
        } else {
            $this->payment = null;
            \Log::error($gateway . '当前支付回调有问题，没有返回订单号');
        }
        parent::initialize($this->payment, $gateway);
        
        // if ($this->data_map) {
        // $data_map_flipped = array_flip($this->data_map);
        // foreach ($data_map_flipped as $key => $value) {
        // if (Str::startsWith($key, ':')) {
        // unset($data_map_flipped[$key]);
        // }
        // }
        // $this->data_map = array_merge($this->data_map, $data_map_flipped);
        // Log::info(class_basename($this) . ':data_map:', $this->data_map);
        // }
        
        return $this;
    }

    /**
     * handle the webhook
     *
     * @return ResponseInterface
     */
    public function handle()
    {
        $id = $this->getValue('pay_order_number', null, $this->data);
        \Log::info('webhook handle:' . $id);
        if ($this->pay_order_number) {
            if ($this->pay_order_number != $id) {
                // error
                \Log::info('id not the same: ' . $id);
                return;
            }
        } else {
            $this->pay_order_number = $id;
        }
        \Log::info('payment_id:' . $this->pay_order_number);
        $data = array();
        if ($this->isValid()) {
            if ($this->payment) {
                $updated = $this->getUpdated();
                if ($updated) {
                    $this->payment->forceFill($updated);
                    $is_updated = $this->payment->saveOrFail();
                    if ($is_updated) {
                        \Log::info('uupdated successfully', $this->payment->toArray());
                    } else {
                        \Log::info('failed to updated');
                    }
                }
                $data = $this->payment;
                
                $class_name = class_basename($this);
                if (Str::endsWith($class_name, 'Request')) {
                    // $action = substr($class_name, -1, strlen('Request') - 1);
                    
                    $action = substr($class_name, 0, strlen($class_name) - strlen('Request'));
                    $response_class = \App\Vendor\Pay\Utils::class_namespace($this) . "\\$action" . 'Response';
                    $this->response = new $response_class($this, $data);
                    
                    if (method_exists($this->response, 'handle')) {
                        $this->response->handle();
                    }
                    return $this->response;
                }
            } else {
                \Log::info('payment not found:' . $this->pay_order_number);
            }
        } else {
            \Log::info('failed to validate');
        }
        
        \Log::info('notify handle done');
        
        return null;
    }

    protected function getUpdated()
    {
        $updated = [];
        if ($this->updateable) {
            $this->payment_status = $this->getStatus();
            if ($this->payment_status) {
                if ($this->status == 0) {
                    if ($this->checkPaymentStatus()) {
                        $updated['status'] = PaymentStatus::COMPLETED;
                    } else {
                        $updated['status'] = PaymentStatus::FAILED;
                        \Log::info('failed to validate payment status');
                    }
                    $updated['operate_time'] = Carbon::now();
                    $pay_order_channel_trade_number = $this->getValue('pay_order_channel_trade_number', null,
                        $this->data);
                    if ($pay_order_channel_trade_number) {
                        $updated['pay_order_channel_trade_number'] = $pay_order_channel_trade_number;
                    }
                    
                    \Log::info('payment updated attributes', $updated);
                    
                    $updated = Arr::only($updated, $this->updateable);
                }
            }
        }
        return $updated;
    }

    public function getStatus()
    {
        $reqData = $this->getRequestData();
        
        return array_key_exists($this->statusKey, $reqData) ? $reqData[$this->statusKey] : '';
    }

    public function isConfirming()
    {
        if ($this->getStatus() == BaseDepositModel::ORDER_STATUS_PAY_SUCCEED &&
             $this->payment->status_id != BaseDepositModel::ORDER_STATUS_PAY_SUCCEED) {
            return true;
        }
        
        return false;
    }

    protected function checkPaymentStatus()
    {
        return ($this->statusCode == $this->payment_status);
    }

    public function getRequestData()
    {
        $request = request();
        \Log::info('getRequestData:server:', $request->server->all());
        if ('GET' == $this->method) {
            return $request->query->all();
        } else if ('POST' == $this->method) {
            return $request->all();
        } else {
            return $request->input();
        }
    }

    /**
     * Get the raw data array for this message.
     * The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    public function isValid()
    {
        if (is_null($this->is_valid)) {
            return $this->validate();
        }
        
        return $this->is_valid;
    }

    public function getPayment()
    {
        return $this->payment;
    }

    protected function validate()
    {
        $ret = true;
        foreach ($this->validatable as $validate) {
            if (method_exists($this, 'validate' . ucfirst($validate))) {
                $method = 'validate' . ucfirst($validate);
                return $this->$method();
            }
            
            if (! $ret) {
                break;
            }
        }
        
        $this->is_valid = $ret;
        return $this->is_valid;
    }
}