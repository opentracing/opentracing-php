<?php

namespace OpenTracing\Mock\Tests;

use OpenTracing\NoopScopeManager;
use OpenTracing\Mock\MockSpan;
use OpenTracing\Mock\MockSpanContext;
use PHPUnit_Framework_TestCase;

/**
 * @covers MockSpan
 */
final class MockSpanTest extends PHPUnit_Framework_TestCase
{
    const OPERATION_NAME = 'test';
    const DURATION = 10;

    public function testCreateSpanSuccess()
    {
        $startTime = time();
        $span = new MockSpan(
            NoopScopeManager::create(),
            self::OPERATION_NAME,
            MockSpanContext::createAsRoot(),
            $startTime
        );
        $this->assertEquals($startTime, $span->getStartTime());
    }

    public function testSpanIsFinished()
    {
        $startTime = time();
        $span = new MockSpan(
            NoopScopeManager::create(),
            self::OPERATION_NAME,
            MockSpanContext::createAsRoot(),
            $startTime
        );
        $span->finish($startTime + self::DURATION);
        $this->assertTrue($span->isFinished());
        $this->assertEquals(self::DURATION, $span->getDuration());
    }
}
