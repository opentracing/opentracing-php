<?php

namespace OpenTracingMock\Tests;

use OpenTracingMock\Span;
use OpenTracingMock\SpanContext;
use PHPUnit_Framework_TestCase;

final class SpanTest extends PHPUnit_Framework_TestCase
{
    const OPERATION_NAME = 'test';
    const DURATION = 10;

    public function testCreateSpanSuccess()
    {
        $startTime = time();
        $span = Span::create(self::OPERATION_NAME, SpanContext::createAsRoot(), $startTime);
        $this->assertEquals($startTime, $span->getStartTime());
    }

    public function testSpanIsFinished()
    {
        $startTime = time();
        $span = Span::create(self::OPERATION_NAME, SpanContext::createAsRoot(), $startTime);
        $span->finish($startTime + self::DURATION);
        $this->assertTrue($span->isFinished());
        $this->assertEquals(self::DURATION, $span->getDuration());
    }
}
