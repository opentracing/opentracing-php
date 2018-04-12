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
    const TAG_KEY = 'test_key';
    const TAG_VALUE = 'test_value';
    const LOG_FIELD = 'test_log';

    public function testCreateSpanSuccess()
    {
        $startTime = time();
        $span = new MockSpan(
            self::OPERATION_NAME,
            MockSpanContext::createAsRoot(),
            $startTime
        );
        $this->assertEquals($startTime, $span->getStartTime());
        $this->assertEmpty($span->getTags());
        $this->assertEmpty($span->getLogs());
    }

    public function testAddTagsAndLogsToSpanSuccess()
    {
        $span = new MockSpan(
            self::OPERATION_NAME,
            MockSpanContext::createAsRoot()
        );

        $span->setTag(self::TAG_KEY, self::TAG_VALUE);
        $span->log([self::LOG_FIELD]);

        $this->assertEquals([self::TAG_KEY => self::TAG_VALUE], $span->getTags());
        $this->assertEquals(self::LOG_FIELD, $span->getLogs()[0]['fields'][0]);
    }

    public function testSpanIsFinished()
    {
        $startTime = time();
        $span = new MockSpan(
            self::OPERATION_NAME,
            MockSpanContext::createAsRoot(),
            $startTime
        );
        $span->finish($startTime + self::DURATION);
        $this->assertTrue($span->isFinished());
        $this->assertEquals(self::DURATION, $span->getDuration());
    }
}
