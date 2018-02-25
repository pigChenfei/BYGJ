<?php

namespace App\Vendor\Pay\Gateways;


use Illuminate\Support\Str;

abstract class AbstractGateway
{
    protected $config = []; //credentia

    protected $ip_whitelist = [];

    protected $parameters_map = [
        'amount' => 'amount',
    ];

    protected $create = [
        'REQUEST' => [
            'METHOD' => 'POST',
            'CONTENT_TYPE' => 'json',
            'URL' => '',
            'PARAMETERS' => []
        ],
        'RESPONSE' => [
            'CONTENT_TYPE' => 'json',
            'PARAMETERS' => [
            ]
        ]
    ];

    protected $query = [
        'REQUEST' => [
            'METHOD' => 'POST',
            'URL' => '',
            'PARAMETERS' => []
        ],
    ];


    protected $back = [
        'REQUEST' => [
            'METHOD' => 'POST',
            'URL' => '',
            'PARAMETERS' => []
        ],
    ];

    protected $postback = [
        'REQUEST' => [
            'METHOD' => 'POST',
            'CONTENT_TYPE' => 'json',
            'URL' => '',
            'PARAMETERS' => []
        ],
        'RESPONSE' => [
            'BODY' => 'ok'
        ]
    ];

    protected $state_map = [];

    protected $data_map = [];

    public function __call($method, $parameters)
    {
        if (Str::startsWith($method, 'can')) {
            $request_class = __NAMESPACE__ . ucfirst($method);
            return class_exists($request_class);
        } else {
            $request_class = __NAMESPACE__ . '\\' . ucfirst($method) . 'Request';
            if (class_exists($request_class)) {
                $request = new $request_class($parameters, $this->data_map);
                return $request->send();
            }
        }
    }

}