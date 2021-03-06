<?php

namespace Brofist\ApiClient;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class Json implements JsonInterface
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
            throw new \InvalidArgumentException('Endpoint not set');
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

    public function get($path, array $params = [], array $options = [])
    {
        return $this->request('GET', $path, array_merge($options, ['query' => $params]));
    }

    public function post($path, array $postData = [], array $options = [])
    {
        return $this->request('POST', $path, array_merge($options, ['json' => $postData]));
    }

    public function put($path, array $putData = [], array $options = [])
    {
        return $this->request('PUT', $path, array_merge($options, ['json' => $putData]));
    }

    public function delete($endpoint, array $data = [], array $options = [])
    {
        throw new \BadMethodCallException('Not implemented yet');
    }

    public function patch($endpoint, array $data = [], array $options = [])
    {
        throw new \BadMethodCallException('Not implemented yet');
    }

    /**
     * @param string $method
     * @param string $path
     * @param array  $options
     *
     * @throws InvalidJsonResponseBodyException
     * @throws Exception
     *
     * @return array
     */
    protected function request($method, $path, $options)
    {
        try {
            $uri = $this->endpoint . '/' .  ltrim($path, '/');
            $options = array_merge($options, $this->additionalOptions);
            $response = $this->httpClient->request($method, $uri, $options);

            return $this->getJsonContentFromResponse($response);
        } catch (RequestException $e) {
            $message = $e->getMessage();
            $data = json_decode($e->getResponse()->getBody(), true);

            if (isset($data['message'])) {
                $message = $data['message'];
            }

            throw new Exception($message, null, $e);
        }
    }

    /**
     * @param ResponseInterface $response
     *
     * @throws InvalidJsonResponseBodyException
     *
     * @return array
     */
    private function getJsonContentFromResponse(ResponseInterface $response)
    {
        $body = $response->getBody();
        $content = json_decode($body, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidJsonResponseBodyException($body);
        }

        return $content;
    }
}
