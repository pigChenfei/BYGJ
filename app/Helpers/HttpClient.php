<?php

namespace App\Helpers;

class HttpClient
{
    public $options = [];

    /**
     * Visit the given URI with a XML request.
     *
     * @param  string $method
     * @param  string $uri
     * @param  array $data
     * @param  array $headers
     * @return $this
     */
    public function xml($uri, $data = '', array $headers = [])
    {
        $headers['Content-Type'] = 'application/xml';
        $options['headers'] = $headers;
        if ($data) {
            if (is_string($data)) {
                $options['body'] = $data;
            } elseif (is_array($data)) {
                //todo
            }
        }

        return $this->call('POST', $uri, $options);
    }

    /**
     * Visit the given URI with a JSON request.
     *
     * @param  string $method
     * @param  string $uri
     * @param  array $data
     * @param  array $headers
     * @return $this
     */
    public function json($uri, array $data = [], array $headers = [])
    {
        return $this->call(
            'POST', $uri, ['json' => $data, 'headers' => $headers]
        );
    }

    /**
     * Visit the given URI with a form request.
     *
     * @param  string $method
     * @param  string $uri
     * @param  array $data
     * @param  array $headers
     * @return $this
     */
    public function form_post($uri, array $data = [], array $headers = [])
    {
        return $this->call(
            'POST', $uri, ['form_params' => $data, 'headers' => $headers]
        );
    }

    /**
     * Visit the given URI with a form request.
     *
     * @param  string $method
     * @param  string $uri
     * @param  array $data
     * @param  array $headers
     * @return $this
     */
    public function form_get($uri, array $data = [], array $headers = [])
    {
        return $this->call(
            'GET', $uri, ['form_params' => $data, 'headers' => $headers]
        );
    }

    /**
     * Visit the given URI with a GET request.
     *
     * @param  string $uri
     * @param  array $headers
     * @return $this
     */
    public function get($uri, $data, array $headers = [])
    {
        return $this->call(
            'GET', $uri, ['query' => $data, 'headers' => $headers]);
    }

    /**
     * Visit the given URI with a POST request.
     *
     * @param  string $uri
     * @param  array $data
     * @param  array $headers
     * @return $this
     */
    public function post($uri, $data, array $headers = [])
    {
        return $this->call('POST', $uri, ['body' => $data, 'headers' => $headers]);
    }

    /**
     * Visit the given URI with a PUT request.
     *
     * @param  string $uri
     * @param  array $data
     * @param  array $headers
     * @return $this
     */
    public function put($uri, $data, array $headers = [])
    {
        return $this->call('PUT', $uri, ['body' => $data, 'headers' => $headers]);
    }

    /**
     * Visit the given URI with a PATCH request.
     *
     * @param  string $uri
     * @param  array $data
     * @param  array $headers
     * @return $this
     */
    public function patch($uri, array $data = [], array $headers = [])
    {
        return $this->call('PATCH', $uri, ['body' => $data, 'headers' => $headers]);
    }

    /**
     * Visit the given URI with a DELETE request.
     *
     * @param  string $uri
     * @param  array $data
     * @param  array $headers
     * @return $this
     */
    public function delete($uri, array $data = [], array $headers = [])
    {
        return $this->call('DELETE', $uri, ['body' => $data, 'headers' => $headers]);
    }

    public function withQuery($query)
    {
        $this->options['query'] = $query;
        return $this;
    }

    /**
     * Call the given URI and return the Response.
     *
     * @param  string $method
     * @param  string $uri
     * @param  array $parameters
     * @param  array $cookies
     * @param  array $files
     * @param  array $server
     * @param  string $content
     * @return \Illuminate\Http\Response
     */
    public function call($method, $uri, array $options = [])
    {
        $client = new \GuzzleHttp\Client();
        return $client->request($method, $uri, $options + $this->options);
    }

}