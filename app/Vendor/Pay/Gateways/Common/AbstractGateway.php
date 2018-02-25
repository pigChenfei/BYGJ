<?php
namespace App\Vendor\Pay\Gateways\Common;

use App\Helpers\HttpClient;
use App\Models\Log\Base\BaseDepositModel;
use App\Vendor\Pay\Utils;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

abstract class AbstractGateway
{

    public $data_map = [];

    public $merchant_config = null;

    public function __construct(array $merchant_config = null)
    {
        $this->merchant_config = $merchant_config;
    }

    /**
     * Handle dynamic method calls into the model.
     *
     * @param string $method            
     * @param array $parameters            
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (Str::startsWith($method, 'can')) {
            $request_class = Utils::class_namespace($this) . '\\Messages\\' . ucfirst(substr($method, 3)) . 'Request';
            return class_exists($request_class);
        } else {
            Log::info("AbstractGateway called: " . class_basename($this));
            $request_class = Utils::class_namespace($this) . '\\Messages\\' . ucfirst($method) . 'Request';
            // Log::info("request_class=$request_class", $parameters);
            if (class_exists($request_class)) {
                if ('back' == $method) {
                    // do nothing
                } else if (is_array($parameters) && count($parameters) > 0) {
                    $parameters = $parameters[0];
                    if ($parameters instanceof Model) {
                        // $parameters = $parameters->toArray();
                    }
                } else {
                    $parameters = [];
                }
                $request = new $request_class($parameters, $this);
                return $request->handle();
            }
        }
    }

    public function name()
    {
        $namespace = Utils::class_namespace($this);
        return substr($namespace, strrpos($namespace, '\\') + 1);
    }

    public function getConfig($key)
    {
        Log::info('gateway config:' . $this->configPrefix() . $key);
        // dd($this->configPrefix());
        return config($this->configPrefix() . $key);
    }

    public function configPrefix()
    {
        return 'gateway.' . strtolower($this->name()) . '.';
    }

    public function pay(BaseDepositModel $payment)
    {
        // check what missed
        $response = $this->create($payment);
        if ($response->isSuccessful()) {
            // payment is complete
        } elseif ($response->isRedirect()) {
            $response->redirect(); // this will automatically forward the customer
        } else {
            // not successful
        }
    }

    protected function getParameters($action, $payment)
    {
        $parameters = [];
        $parameters_config = $this->$action['PARAMETERS'];
        foreach ($parameters_config as $key => $value) {
            if (Str::startsWith(static::PREFIX_START, $key)) {
                $key = substr($key, 3);
            }
            if (Str::startsWith(static::PREFIX_CONFIG, $value)) {
                $parameters[$key] = config(substr($value, 3));
            } elseif (Str::startsWith(static::PREFIX_PAYMENT_PROPERTY, $value)) {
                $parameters[$key] = $payment->$value;
            } elseif (Str::startsWith(static::PREFIX_PAYMENT_METHOD, $value)) {
                $parameters[$key] = $payment->{$value}($parameters);
            } elseif (Str::startsWith(static::PREFIX_INTERNAL_PARAMTER, $value)) {
                $parameters[$key] = $this->getParameter($action, $value, $parameters);
            }
        }
        
        return $parameters;
    }

    protected function getParameter($action, $key)
    {}
}