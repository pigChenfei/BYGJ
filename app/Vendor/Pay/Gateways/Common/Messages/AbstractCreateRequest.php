<?php

namespace App\Vendor\Pay\Gateways\Common\Messages;
use App\Payment\Gateways\Common\Messages\Traits\HasDatamap;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Helpers\HttpClient;
use GuzzleHttp\ClientInterface;
use RuntimeException;

abstract class AbstractCreateRequest implements RequestInterface
{
    use HasDatamap;

    public $url = null;

    public $method = null;

    public $content_type = null;

    public $parameters_common = [];

    public $model = null;

    /**
     * Create a new Request
     *
     * @param ClientInterface $httpClient  A Guzzle client to make API calls with
     * @param HttpRequest     $httpRequest A Symfony HTTP request object
     */
    public function __construct(array $parameters = [], array $data_map = [])
    {
        $this->initialize($parameters, $data_map);
    }

    /**
     * Initialize the object with parameters.
     *
     * If any unknown parameters passed, they will be ignored.
     *
     * @param array $parameters An associative array of parameters
     *
     * @return $this
     * @throws RuntimeException
     */
    public function initialize(array $parameters = [], array $data_map = [])
    {
        if (null !== $this->response) {
            throw new RuntimeException('Request cannot be modified after it has been sent!');
        }

        $this->parameters = $parameters;
        if (count($parameters) > 0) {
            if ($parameters[0] instanceof Model) {
                $this->model = $parameters[0];
                $this->parameters = $this->model->toArray();
            }
        }
        $this->data_map = $data_map;

        return $this;
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->getDataByMap(array_merge($this->parameters_common, $this->parameters));
    }

    /**
     * Send the request
     *
     * @return ResponseInterface
     */
    public function send()
    {
        $data = $this->getData();

        return $this->sendData($data);
    }

    /**
     * Get the associated Response.
     *
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed             $data The data to send
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        $url = $this->getURL();
        if ($this->method && $url) {
            $client = new HttpClient();
            if (isset($this->request['CONTENT_TYPE'])) {
                $type = $this->request['CONTENT_TYPE'];
                $data = $client->$type($this->method, $url, $data);
            } else {
                $data = $client->{$this->method}($url, $data);
            }
        }

        $class_name = class_basename($this);
        if (Str::endsWith($class_name, 'Request')) {
            $action = substr($class_name, strlen('Request'));
            $response_class = $action . 'Response';
            return $this->response = new $response_class($this, $data);
        }

        return null;
    }

    protected function getURL()
    {
        if ($this->url && Str::startsWith(static::PREFIX_CONFIG, $this->url)) {
            return config(substr($this->url, 3));
        }

        return $this->url;
    }
}