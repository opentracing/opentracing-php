<?php

declare(strict_types=1);

namespace OpenTracing\Tests\Mock;

use OpenTracing\InvalidReferenceArgumentException;
use OpenTracing\UnsupportedFormatException;
use OpenTracing\Mock\MockTracer;
use OpenTracing\NoopSpanContext;
use OpenTracing\Reference;
use OpenTracing\Span;
use OpenTracing\SpanContext;
use PHPUnit\Framework\TestCase;

/**
 * @covers MockTracer
 */
final class MockTracerTest extends TestCase
{
    private const OPERATION_NAME = 'test_name';
    private const FORMAT = 'test_format';

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

    public function testStartSpanWithReference(): void
    {
        $tracer = new MockTracer();
        $tracer->startSpan('parent_name');
        /** @var Span $parentSpan */
        $parentSpan = $tracer->getSpans()[0];
        $tracer->startSpan(
            self::OPERATION_NAME,
            ['references' => [Reference::createForSpan(Reference::CHILD_OF, $parentSpan)]]
        );
        $activeSpan = $tracer->getActiveSpan();

        self::assertNull($activeSpan);
    }

    public function testStartSpanWithReferenceWithoutExpectedContextType(): void
    {
        $tracer = new MockTracer();
        $notAMockContext = new NoopSpanContext();

        $this->expectException(InvalidReferenceArgumentException::class);

        $tracer->startSpan(
            self::OPERATION_NAME,
            ['references' => [new Reference(Reference::CHILD_OF, $notAMockContext)]]
        );
    }

    public function testInjectWithNoInjectorsFails()
    {
        $tracer = new MockTracer();
        $span = $tracer->startSpan(self::OPERATION_NAME);
        $carrier = [];

        $this->expectException(UnsupportedFormatException::class);
        $tracer->inject($span->getContext(), self::FORMAT, $carrier);
    }

    public function testInjectSuccess()
    {
        $actualSpanContext = null;
        $actualCarrier = null;

        $injector = function ($spanContext, &$carrier) use (&$actualSpanContext, &$actualCarrier) {
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

        $this->expectException(UnsupportedFormatException::class);
        $tracer->extract(self::FORMAT, $carrier);
    }

    public function testExtractSuccess()
    {
        $actualSpanContext = null;
        $actualCarrier = null;

        $extractor = function ($carrier) use (&$actualCarrier) {
            $actualCarrier = $carrier;
            return new NoopSpanContext();
        };

        $tracer = new MockTracer([], [self::FORMAT => $extractor]);
        $carrier = [
            'TRACE_ID' => 'trace_id'
        ];

        $spanContext = $tracer->extract(self::FORMAT, $carrier);

        $this->assertInstanceOf(SpanContext::class, $spanContext);
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
