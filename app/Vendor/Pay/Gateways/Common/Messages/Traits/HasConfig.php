<?php

namespace App\Vendor\Pay\Gateways\Common\Messages\Traits;


trait HasConfig
{
    protected $config_prefix = '';
    public function setConfigPrefix($config_prefix)
    {
        $this->config_prefix = $config_prefix;
    }

    public function config($key)
    {
        return config($this->config_prefix . $key);
    }
}