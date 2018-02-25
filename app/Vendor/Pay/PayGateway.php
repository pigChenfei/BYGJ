<?php

namespace App\Vendor\Pay;

use Illuminate\Support\Facades\Log;

class PayGateway
{
    public static function gateway($gateway_name)
    {

        $gateway_class = '\\App\\Vendor\\Pay\Gateways\\' . ucfirst($gateway_name) . '\Gateway';
        Log::info("gateway_class=$gateway_class");
        if (class_exists($gateway_class)) {
            Log::info("gateway_class found");
            return new $gateway_class();
        }

        Log::info("gateway_class not found");
        return null;
    }
}