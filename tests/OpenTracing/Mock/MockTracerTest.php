<?php

namespace OpenTracing\Mock\Tests;

use OpenTracing\Exceptions\UnsupportedFormat;
use OpenTracing\Mock\MockTracer;
use OpenTracing\NoopSpan;
use PHPUnit_Framework_TestCase;

/**
 * @covers MockTracer
 */
final class MockTracerTest extends PHPUnit_Framework_TestCase
{
    const OPERATION_NAME = 'test_name';
    const FORMAT = 'test_format';

    public function testStartActiveSpanSuccess()
    {
        $tracer = new MockTracer();
        $scope = $tracer->startActiveSpan(self::OPERATION_NAME);
        $activeSpan = $tracer->getActiveSpan();
        $this->assertEquals($scope->getSpan(), $activeSpan);
    }

    public function testStartSpanSuccess()
    {
        $tracer = new MockTracer();
        $tracer->startSpan(self::OPERATION_NAME);
        $activeSpan = $tracer->getActiveSpan();
        $this->assertNull($activeSpan);
    }

    public function testInjectWithNoInjectorsFails()
    {
        $tracer = new MockTracer();
        $span = $tracer->startSpan(self::OPERATION_NAME);
        $carrier = [];
        $this->expectException(UnsupportedFormat::class);
        $tracer->inject($span->getContext(), self::FORMAT, $carrier);
    }

    public function testInjectSuccess()
    {
        $actualSpanContext = null;
        $actualCarrier = null;

        $injector = function ($spanContext, $carrier) use (&$actualSpanContext, &$actualCarrier) {
            $actualSpanContext = $spanContext;
            $actualCarrier = $carrier;
        };

        $tracer = new MockTracer([self::FORMAT => $injector]);
        $span = $tracer->startSpan(self::OPERATION_NAME);
        $carrier = [];
        $tracer->inject($span->getContext(), self::FORMAT, $carrier);

        $this->assertSame($span->getContext(), $actualSpanContext);
        $this->assertSame($carrier, $actualCarrier);
    }

    public function testExtractWithNoExtractorsFails()
    {
        $tracer = new MockTracer();
        $carrier = [];
        $this->expectException(UnsupportedFormat::class);
        $tracer->extract(self::FORMAT, $carrier);
    }

    public function testExtractSuccess()
    {
        $actualSpanContext = null;
        $actualCarrier = null;

        $extractor = function ($carrier) use (&$actualCarrier) {
            $actualCarrier = $carrier;
            return NoopSpan::create();
        };

        $tracer = new MockTracer([], [self::FORMAT => $extractor]);
        $carrier = [
            'TRACE_ID' => 'trace_id'
        ];

        $tracer->extract(self::FORMAT, $carrier);
    }

    public function testFlushSuccess()
    {
        $tracer = new MockTracer();
        $tracer->startSpan(self::OPERATION_NAME);
        $this->assertCount(1, $tracer->getSpans());
        $tracer->flush();
        $this->assertCount(0, $tracer->getSpans());
    }
}
