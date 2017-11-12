<?php

namespace OpenTracingTests\Unit\Carriers;

use GuzzleHttp\Psr7\Request;
use OpenTracing\Carriers\HttpHeaders;
use PHPUnit_Framework_TestCase;

/**
 * @covers HttpHeaders
 */
final class HttpHeadersTest extends PHPUnit_Framework_TestCase
{
    const TEST_HEADERS = ['foo' => 'bar'];

    public function testCreationFromRequestHasTheExpectedValues()
    {
        $request = new Request('GET', '', self::TEST_HEADERS);
        $httpHeaders = HttpHeaders::fromRequest($request);

        foreach ($httpHeaders as $key => $value) {
            $this->assertEquals('foo', $key);
            $this->assertEquals('bar', $value);
        }
    }
}
