<?php

namespace BrofistTest\ApiClient;

use Brofist\ApiClient\Exception;
use Brofist\ApiClient\Json;
use Brofist\ApiClient\JsonInterface;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use PHPUnit_Framework_TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @SuppressWarnings(PHPMD)
 */
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
    public function implementsTheCorrectInterface()
    {
        $this->assertInstanceOf(JsonInterface::class, $this->client);
    }

    /**
     * @test
     */
    public function canResolvePathWithNonLeadingSlash()
    {
        $query = ['foo' => 'bar'];

        $this->mockClient()
            ->request(
                'GET',
                $this->url('/foo'),
                ['query' => $query]
            )
            ->willReturn($this->fooBarResponse());

        $data = $this->client->get('foo', $query);

        $this->assertFooBarResponse($data);
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
    public function canMakeGetRequestsWithBasicAuthentication()
    {
        $this->setClient(['basicAuth' => ['username', 'password']]);

        $query = ['foo' => 'bar'];

        $this->mockClient()->request(
            'GET',
            $this->url('/foo'),
            [
                'auth'  => ['username', 'password'],
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
                'json' => $postData,
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
     * @test
     * @expectedException \BadMethodCallException
     */
    public function deleteIsNotImplemented()
    {
        $this->client->delete('endpoint');
    }

    /**
     * @test
     * @expectedException \BadMethodCallException
     */
    public function patchIsNotImplemented()
    {
        $this->client->patch('endpoint');
    }

    /**
     * @test
     */
    public function convertsGuzzleResponseExceptionsIntoFriendlyClientExceptions()
    {
        $request = $this->prophesize(RequestInterface::class);
        $response = $this->getMockResponse(['message' => 'theMessage']);
        $response->getStatusCode()->willReturn(200);

        $originalException = new RequestException(
            'exception message',
            $request->reveal(),
            $response->reveal()
        );

        $this->mockClient()
            ->request('GET', $this->url('/foo'), ["query" => []])
            ->willThrow($originalException);

        try {
            $this->client->get('/foo');
            $this->fail('Should have thrown exception');
        } catch (\Exception $e) {
            $this->assertInstanceOf(Exception::class, $e);
            $this->assertEquals('theMessage', $e->getMessage());
            $this->assertSame($originalException, $e->getPrevious());
        }
    }

    /**
     * @test
     * @expectedException \Brofist\ApiClient\InvalidJsonResponseBodyException
     */
    public function throwsInvalidJsonResponse()
    {
        $this->mockClient()->request('GET', $this->url('/foo'), ["query" => []])
            ->willReturn($this->mockResponseBody('invalid')->reveal());

        $this->client->get('/foo');
    }

    /**
     * @test
     */
    public function passesOnHeaders()
    {
        $options = [
            'headers' => ['Content-Type' => 'application/json; charset=utf-8'],
            'query'   => []
        ];
        $this->mockClient()->request('GET', $this->url('/foo'), $options)
            ->willReturn($this->fooBarResponse());

        $data = $this->client->get('/foo', [], ['headers' => ['Content-Type' => 'application/json; charset=utf-8']]);

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
        return $this->mockResponseBody('{"foo":"bar"}')->reveal();
    }

    private function mockResponseBody($responseBody)
    {
        $response = $this->prophesize(ResponseInterface::class);
        $response->getBody()->willReturn($responseBody);

        return $response;
    }

    private function getMockResponse(array $data = [])
    {
        return $this->mockResponseBody(json_encode($data));
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
