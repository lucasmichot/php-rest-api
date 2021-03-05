<?php

use MessageBird\Client;
use MessageBird\Common\HttpClient;

class HttpClientTest extends BaseTest
{
    public function testHttpClient()
    {
        $client = new HttpClient(Client::ENDPOINT);

        $url = $client->getRequestUrl('a', null);
        $this->assertSame(Client::ENDPOINT.'/a', $url);

        $url = $client->getRequestUrl('a', ['b' => 1]);
        $this->assertSame(Client::ENDPOINT.'/a?b=1', $url);
    }

    public function testHttpClientInvalidTimeout()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/^Timeout must be an int > 0, got "integer 0".$/');
        new HttpClient(Client::ENDPOINT, 0);
    }

    /**
     * Tests a boundary condition (timeout == 1)
     */
    public function testHttpClientValidTimeoutBoundary()
    {
        new HttpClient(Client::ENDPOINT, 1);

        $this->doAssertionToNotBeConsideredRiskyTest();
    }

    public function testHttpClientInvalidConnectionTimeout()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/^Connection timeout must be an int >= 0, got "stdClass".$/');
        new HttpClient(Client::ENDPOINT, 10, new \stdClass());
    }

    /**
     * Tests a boundary condition (connectionTimeout == 0)
     */
    public function testHttpClientValidConnectionTimeoutBoundary()
    {
        new HttpClient(Client::ENDPOINT, 10, 0);

        $this->doAssertionToNotBeConsideredRiskyTest();
    }

    /**
     * Test that requests can only be made when there is an Authentication set
     */
    public function testHttpClientWithoutAuthenticationException()
    {
        $this->expectException(\MessageBird\Exceptions\AuthenticateException::class);
        $this->expectExceptionMessageMatches('/Can not perform API Request without Authentication/');
        $client = new HttpClient(Client::ENDPOINT);
        $client->performHttpRequest('foo', 'bar');
    }

    /**
     * Test that we can set and get the httpOptions
     */
    public function testHttpClientWithHttpOptions()
    {
        $client = new HttpClient(Client::ENDPOINT);
        $url = '127.0.0.1:8080';

        $client->addHttpOption(CURLOPT_PROXY, $url);

        $this->assertSame($client->getHttpOption(CURLOPT_PROXY), $url);
    }
}
