<?php

namespace OpenTracingTests\Unit\Carriers;

use GuzzleHttp\Psr7\Request;
use OpenTracing\Carriers\HttpHeaders;
use PHPUnit_Framework_TestCase;

final class HttpHeadersTest extends PHPUnit_Framework_TestCase
{
    const TEST_HEADERS = ['foo' => 'bar'];

    private $headers;
    /** @var HttpHeaders */
    private $httpHeaders;
    private $keys;
    private $request;

    public function testCreationWithHeadersHasTheExpectedValues()
    {
        $this->givenAListOfHeaders();
        $this->whenCreatingAHttpHeadersCarrier();
        $this->thenTheCarrierHasTheExpectedKeys();
    }

    public function testCreationFromRequestHasTheExpectedValues()
    {
        $this->givenARequestWithHeaders();
        $this->whenCreatingAHttpHeadersCarrierFromRequest();
        $this->thenTheCarrierHasTheExpectedKeys();
    }

    private function givenAListOfHeaders()
    {
        $this->headers = self::TEST_HEADERS;
    }

    private function givenARequestWithHeaders()
    {
        $this->request = new Request('GET', '', self::TEST_HEADERS);
    }

    private function whenCreatingAHttpHeadersCarrier()
    {
        $this->httpHeaders = HttpHeaders::withHeaders($this->headers);
    }

    private function whenCreatingAHttpHeadersCarrierFromRequest()
    {
        $this->httpHeaders = HttpHeaders::fromRequest($this->request);
    }

    private function thenTheCarrierHasTheExpectedKeys()
    {
        $keys = $this->keys;

        $this->httpHeaders->foreachKey(function ($key) use (&$keys) {
            $keys[] = $key;
        });

        $this->assertEquals(['foo'], $keys);
    }
}
