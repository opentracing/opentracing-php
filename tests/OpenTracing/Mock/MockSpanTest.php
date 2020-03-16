<?php

declare(strict_types=1);

namespace OpenTracing\Tests\Mock;

use OpenTracing\Mock\MockSpan;
use OpenTracing\Mock\MockSpanContext;
use PHPUnit\Framework\TestCase;

/**
 * @covers MockSpan
 */
final class MockSpanTest extends TestCase
{
    private const OPERATION_NAME = 'test';
    private const DURATION = 10;
    private const TAG_KEY = 'test_key';
    private const TAG_VALUE = 'test_value';
    private const LOG_FIELD = 'test_log';

    public function testCreateSpanSuccess()
    {
        $startTime = time();
        $span = new MockSpan(self::OPERATION_NAME, MockSpanContext::createAsRoot(), $startTime);

        $this->assertEquals($startTime, $span->getStartTime());
        $this->assertEmpty($span->getTags());
        $this->assertEmpty($span->getLogs());
    }

    public function testAddTagsAndLogsToSpanSuccess()
    {
        $span = new MockSpan(self::OPERATION_NAME, MockSpanContext::createAsRoot());

        $span->setTag(self::TAG_KEY, self::TAG_VALUE);
        $span->log([self::LOG_FIELD]);

        $this->assertEquals([self::TAG_KEY => self::TAG_VALUE], $span->getTags());
        $this->assertEquals(self::LOG_FIELD, $span->getLogs()[0]['fields'][0]);
    }

    public function testSpanIsFinished()
    {
        $startTime = time();
        $span = new MockSpan(self::OPERATION_NAME, MockSpanContext::createAsRoot(), $startTime);
        $span->finish($startTime + self::DURATION);

        $this->assertTrue($span->isFinished());
        $this->assertEquals(self::DURATION, $span->getDuration());
    }
}
