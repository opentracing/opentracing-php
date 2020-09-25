<?php

declare(strict_types=1);

namespace OpenTracing\Tests\Mock;

use OpenTracing\Mock\MockSpanContext;
use PHPUnit\Framework\TestCase;

final class MockSpanContextTest extends TestCase
{
    private const TRACE_ID = 123987;
    private const SPAN_ID = 555632;
    private const IS_SAMPLED = true;
    private const BAGGAGE_ITEM_KEY = 'test_key';
    private const BAGGAGE_ITEM_VALUE = 'test_value';

    public function testCreateAsRootSuccess()
    {
        $parentContext = MockSpanContext::createAsRoot();
        $childContext = MockSpanContext::createAsChildOf($parentContext);

        $this->assertEquals($parentContext->getTraceId(), $childContext->getTraceId());
    }

    public function testCreateMockSpanContextSuccess()
    {
        $spanContext = MockSpanContext::create(
            self::TRACE_ID,
            self::SPAN_ID,
            self::IS_SAMPLED,
            [self::BAGGAGE_ITEM_KEY => self::BAGGAGE_ITEM_VALUE]
        );

        $this->assertEquals($spanContext->getTraceId(), self::TRACE_ID);
        $this->assertEquals($spanContext->getSpanId(), self::SPAN_ID);
        $this->assertEquals($spanContext->isSampled(), self::IS_SAMPLED);
        $this->assertEquals([self::BAGGAGE_ITEM_KEY => self::BAGGAGE_ITEM_VALUE], iterator_to_array($spanContext));
        $this->assertEquals(self::BAGGAGE_ITEM_VALUE, $spanContext->getBaggageItem(self::BAGGAGE_ITEM_KEY));
    }

    public function testAddBaggageItemSuccess()
    {
        $spanContext = MockSpanContext::create(
            self::TRACE_ID,
            self::SPAN_ID,
            self::IS_SAMPLED
        );
        $this->assertEmpty(iterator_to_array($spanContext));

        $spanContext = $spanContext->withBaggageItem(self::BAGGAGE_ITEM_KEY, self::BAGGAGE_ITEM_VALUE);
        $this->assertEquals([self::BAGGAGE_ITEM_KEY => self::BAGGAGE_ITEM_VALUE], iterator_to_array($spanContext));
    }
}
