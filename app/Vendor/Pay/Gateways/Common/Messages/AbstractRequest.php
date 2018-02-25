<?php
namespace App\Vendor\Pay\Gateways\Common\Messages;

use App\Vendor\Pay\Gateways\Common\Messages\Traits\HasDatamap;
use App\Vendor\Pay\Utils;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Helpers\HttpClient;
use GuzzleHttp\ClientInterface;
use RuntimeException;

abstract class AbstractRequest implements RequestInterface
{
    use HasDatamap;

    public $url = null;

    public $method = null;

    public $headers = [];

    public $parameters = [];

    public $is_redirect = false;

    protected $response;

    public $formsubmit = false;

    public $gateway;

    public $gateway_permission = - 1;

    protected $customer_info = null;

    /**
     * Create a new Request
     *
     * @param ClientInterface $httpClient A Guzzle client to make API calls with
     * @param HttpRequest $httpRequest A Symfony HTTP request object
     */
    public function __construct($model, $gateway)
    {
        $this->initialize($model, $gateway);
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
    public function setParameters(array $parameters = array())
    {
        $this->parameters = $parameters;
    }

    public function initialize($model, $gateway)
    {
        $this->gateway = $gateway;
        $this->model = $model;
        $this->data_map = $gateway->data_map;
        
        // Log::info(class_basename($this) . ':data_set:', $this->data_set);
        // Log::info(class_basename($this) . ':data_map:', $this->data_map);
        
        return $this;
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
        return $this->getDataByMap($this->parameters);
    }

    /**
     * Send the request
     *
     * @return ResponseInterface
     */
    public function handle()
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
     * @param mixed $data The data to send
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        $success = false;
        if (! $this->is_redirect) {
            Log::info(class_basename($this) . 'is a api request');
            $url = $this->getURL();
            if ($this->method && $url) {
                $client = new HttpClient();
                $auth = $this->getAuth();
                if ($auth) {
                    $client->options['auth'] = $auth;
                }
                $response = $client->{$this->method}($url, $data, $this->headers);
                
                if ($response) {
                    $data = $this->parseResponse($response);
                    if (200 == $response->getStatusCode() || 201 == $response->getStatusCode()) {
                        Log::info(class_basename($this) . ' response status code is 200');
                        $success = true;
                    } else {
                        Log::info(class_basename($this) . ' response status code is not 200');
                        $this->onFailure($response->getStatusCode(), $data);
                    }
                } else {
                    Log::info(class_basename($this) . ' response is empty');
                    $this->onFailure(0, null);
                }
            } else {
                Log::info(class_basename($this) . ' seems to be invalid becuase method=' . $this->method . ", url=$url");
            }
        } else {
            Log::info(class_basename($this) . 'is a redirect request');
            $success = true;
        }
        if ($success) {
            $class_name = class_basename($this);
            if (Str::endsWith($class_name, 'Request')) {
                $action = substr($class_name, 0, strlen($class_name) - strlen('Request'));
                $response_class = Utils::class_namespace($this) . "\\$action" . 'Response';
                $this->response = new $response_class($this, $data);
                if (method_exists($this->response, 'handle')) {
                    $this->response->handle();
                }
                return $this->response;
            }
        }
        return null;
    }

    protected function parseResponse($response)
    {
        $content_type = $response->getHeader('Content-Type')[0];
        if ($content_type) {
            if (Str::contains($content_type, [
                '/json',
                '+json'
            ])) {
                return \GuzzleHttp\json_decode((string) $response->getBody());
            } else if (Str::contains($content_type, [
                '/xml',
                '+xml'
            ])) {
                return simplexml_load_string((string) $response->getBody());
            }
        }
        
        return (string) $response->getBody();
    }

    protected function onFailure($status_code, $response_body)
    {}

    protected function returnURL($action = null)
    {
        $gateway_name = strtolower($this->gateway->name());
        $id = $this->model->pay_order_number;
        
        return 'http://' . $this->model->carrier->site_url . "/player.pay/return/$gateway_name/$id";
    }

    protected function successURL()
    {
        $gateway_name = strtolower($this->gateway->name());
        $id = $this->model->pay_order_number;
        
        return $this->model->carrier->site_url . "/success/$gateway_name/$id";
    }

    protected function failedURL()
    {
        $gateway_name = strtolower($this->gateway->name());
        $id = $this->model->pay_order_number;
        
        return $this->model->carrier->site_url . "/failed/$gateway_name/$id";
    }

    protected function cancelledURL()
    {
        $gateway_name = strtolower($this->gateway->name());
        $id = $this->model->pay_order_number;
        
        return $this->model->carrier->site_url . "/cancel/$gateway_name/$id";
    }

    protected function webhookURL()
    {
        $gateway_name = strtolower($this->gateway->name());
        $id = $this->model->pay_order_number;
        
        return 'http://' . $this->model->carrier->site_url . "/postback/$gateway_name/$id";
    }

    public function getURL()
    {
        return $this->url ?: $this->getConfig('url.' . $this->action());
    }

    public function getAuth()
    {
        return $this->getConfig('auth');
    }

    public function getConfig($key)
    {
        return $this->gateway->getConfig($key);
    }

    public function action()
    {
        $class_name = class_basename($this);
        return strtolower(substr($class_name, 0, - strlen('Request')));
    }

    protected function thirdPart($key)
    {
        Log::info("pm_id:" . $this->pm_id);
        // dd($this->model->carrierPayChannel->bindedThirdPartGateway);
        return $this->model->carrierPayChannel->bindedThirdPartGateway->$key;
    }

    public function __get($key)
    {
        // Log::info("__get $key:", $this->parameters);
        return $this->getValue($key);
    }
}