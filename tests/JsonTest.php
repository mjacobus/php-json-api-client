<?php

namespace BrofistTest\ApiClient;

use Brofist\ApiClient\Json;
use GuzzleHttp\Client as HttpClient;
use PHPUnit_Framework_TestCase;
use Psr\Http\Message\ResponseInterface;

class JsonTest extends PHPUnit_Framework_TestCase
{
    /** @var Json */
    protected $client;

    /** @var HttpClient | \Prophecy\Prophecy\ObjectProphecy */
    protected $mockClient;

    /**
     * @before
     */
    public function initialize()
    {
        $this->setClient();
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Endpoint not set
     */
    public function throwsExceptionWhenNoEndpointIsGiven()
    {
        new Json();
    }

    /**
     * @test
     */
    public function canMutateEndpoint()
    {
        $this->client = new Json(['endpoint' => 'https://test.foo.bar/v3/']);
        $this->assertEquals('https://test.foo.bar/v3', $this->client->getEndpoint());
    }

    /**
     * @test
     */
    public function canMakeGetRequests()
    {
        $query = ['foo' => 'bar'];

        $this->mockClient()->request(
            'GET',
            $this->url('/foo'),
            [
                'query' => $query,
            ]
        )->willReturn($this->fooBarResponse());

        $data = $this->client->get('/foo', $query);

        $this->assertFooBarResponse($data);
    }

    /**
     * @test
     */
    public function canMakeGetRequestsWithAuthentication()
    {
        $this->setClient(['authToken' => 'myToken']);

        $query = ['foo' => 'bar'];

        $this->mockClient()->request(
            'GET',
            $this->url('/foo'),
            [
                'auth'  => ['myToken', ''],
                'query' => $query,
            ]
        )->willReturn($this->fooBarResponse());

        $data = $this->client->get('/foo', $query);

        $this->assertFooBarResponse($data);
    }

    /**
     * @test
     */
    public function canMakePostRequests()
    {
        $postData = ['foo' => 'bar'];

        $this->mockClient()->request(
            'POST',
            $this->url('/foo'),
            [
                'form_params' => $postData,
            ]
        )->willReturn($this->fooBarResponse());

        $data = $this->client->post('/foo', $postData);

        $this->assertFooBarResponse($data);
    }

    /**
     * @test
     */
    public function canMakePutRequest()
    {
        $putData = ['foo' => 'bar'];

        $this->mockClient()->request(
            'PUT',
            $this->url('/foo'),
            [
                'json' => $putData,
            ]
        )->willReturn($this->fooBarResponse());

        $data = $this->client->put('/foo', $putData);

        $this->assertFooBarResponse($data);
    }

    /**
     * @return \Prophecy\Prophecy\ObjectProphecy
     */
    private function mockClient()
    {
        if ($this->mockClient === null) {
            $this->mockClient = $this->prophesize(HttpClient::class);
        }

        return $this->mockClient;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private function url($path = '/')
    {
        return 'https://endpoint/v1' . $path;
    }

    private function fooBarResponse()
    {
        $response = $this->prophesize(ResponseInterface::class);
        $response->getBody()->willReturn('{"foo":"bar"}');

        return $response->reveal();
    }

    /**
     * @param array $data
     */
    private function assertFooBarResponse($data)
    {
        $this->assertEquals(['foo' => 'bar'], $data);
    }


    private function setClient(array $params = [])
    {
        $default = [
            'endpoint'   => 'https://endpoint/v1/',
            'httpClient' => $this->mockClient()->reveal(),
        ];
        $this->client = new Json(array_merge($default, $params));
    }
}
