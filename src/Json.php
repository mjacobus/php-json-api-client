<?php

namespace Brofist\ApiClient;

use GuzzleHttp\Client as HttpClient;

class Json
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var string
     */
    private $endpoint = '';

    /**
     * @var array
     */
    private $additionalOptions = [];

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        if (!isset($options['endpoint'])) {
            throw new \InvalidArgumentException("Endpoint not set");
        }

        $this->endpoint = trim($options['endpoint'], '/');

        if (isset($options['authToken'])) {
            $this->additionalOptions['auth'] = [$options['authToken'], ''];
        }

        if (isset($options['basicAuth'])) {
            $this->additionalOptions['auth'] = $options['basicAuth'];
        }

        if (!isset($options['httpClient'])) {
            $options['httpClient'] = new HttpClient();
        }

        $this->httpClient = $options['httpClient'];
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param string $path
     * @param array  $params
     *
     * @return array
     */
    public function get($path, array $params = [])
    {
        return $this->request('GET', $path, ['query' => $params]);
    }

    /**
     * @param string $path
     * @param array  $postData
     *
     * @return array
     */
    public function post($path, array $postData)
    {
        return $this->request('POST', $path, ['form_params' => $postData]);
    }

    /**
     * @param string $path
     * @param array  $putData
     *
     * @return array
     */
    public function put($path, array $putData)
    {
        return $this->request('PUT', $path, ['json' => $putData]);
    }

    /**
     * @param string $method
     * @param string $path
     * @param array  $options
     *
     * @return array
     */
    private function request($method, $path, $options)
    {
        $uri = $this->endpoint . $path;

        $options = array_merge($options, $this->additionalOptions);

        $response = $this->httpClient->request($method, $uri, $options);

        return json_decode($response->getBody(), true);
    }
}
