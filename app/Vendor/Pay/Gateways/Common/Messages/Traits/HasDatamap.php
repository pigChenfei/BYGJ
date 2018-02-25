<?php

namespace App\Vendor\Pay\Gateways\Common\Messages\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait HasDatamap
{
    public $model = null;
    protected $data_map = null;

    protected function getDataByMap($keys, $default = null)
    {
        Log::info("getDataByMap", $keys);
        $data = [];
        foreach ($keys as $key) {
            $data[$key] = $this->getValue($key, $default);
        }
        return $data;
    }

    protected function getValue($key = null, $default = null, $data_source = null)
    {
        $key = Arr::exists($this->data_map, $key) ? $this->data_map[$key] : $key;

        return $data_source ? Arr::get($data_source, $key, $default) : $this->getValueByMap($key, $default);
    }

    protected function getValueByMap($key = null, $default = null)
    {
        Log::info("getValueByMap:$key");

        $value = $default;
        if (Str::startsWith($key, 'attribute:')) {
            $method = substr($key, strlen('attribute:'));
            $value = $this->$method();
            Log::info("getValueByMap:$key = $value");
        } elseif (Str::startsWith($key, 'thirdPart:')) {
            $key = substr($key, strlen('thirdPart:'));
            $value = $this->thirdPart($key);
            Log::info("getValueByMap:$key = $value");
        } elseif (Str::startsWith($key, 'merchant_config:')) {
            $key = substr($key, 4);
            $value = $this->mc($key);
            Log::info("getValueByMap:$key = $value");
        } elseif (Str::startsWith($key, 'config:')) {
            $value = $this->getConfig(substr($key, strlen('config:')));
            Log::info("getValueByMap:$key = $value");
        } elseif (Str::startsWith($key, 'const:')) {
            $value = substr($key, strlen('const:'));
        } else {
            //Log::info("getValueByMap:getParameter:$key", $this->model);
            $value = (string)$this->getParameter($key);
        }

        return $value;
    }
//Request URL:https://pay.veritrans-link.com/epayment/payment?acqID=99020344&
//backURL=https%3A%2F%2Fwww.payssion.com%2Fapi%2Fv1%2Fpostback%2Fallpay%2FH522323965205894&
//charSet=UTF-8&frontURL=https%3A%2F%2Fwww.payssion.com%2Fapi%2Fv1%2Freturn%2Fallpay%2FH522323965205894&
//merID=800039289992002&merReserve=H522323965205894&orderAmount=37.95&orderCurrency=CNY&orderNum=H522323965205894&
//paymentSchema=AP&signType=MD5&transTime=20170522055317&transType=PURC&
//version=VER000000002&signature=64b94b8f3c230af1dd2a293005f5f938

//Request URL:https://pay.veritrans-link.com/epayment/payment?acqID=99020344&
//backURL=http%3A%2F%2Fblog.dev%2Fwebhook&
//charSet=UTF-8&frontURL=http%3A%2F%2Fblog.dev%2Freturn&
//merID=800039289992002&merReserve=4&orderAmount=1.28&orderCurrency=USD&orderNum=4&
//paymentSchema=AP&transTime=20170522053121&transType=PURC&
//version=VER000000002&signature=7dcce56e6a2bef40e142d91d5b2815af

//Request URL:https://pay.veritrans-link.com/epayment/payment?acqID=99020344&
//backURL=http%3A%2F%2Fblog.dev%2Fwebhook&frontURL=http%3A%2F%2Fblog.dev%2Freturn&
//merReserve=4&orderAmount=1.28&orderCurrency=USD&orderNum=4&transTime=20170522053121&transType=PURC&
//signature=7f86ed9c332156d22fb6bf51f58d44b5



    protected function getParameter($key, $default = null)
    {
        if (is_object($this->model)) {
            if ($key == 'id') {
                return $this->model->id;
            } else {
                return $this->model->$key;
            }

        }
        return Arr::exists($this->model, $key) ? $this->model[$key] :  $default;
    }
}