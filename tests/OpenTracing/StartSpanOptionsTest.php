<?php

declare(strict_types=1);

namespace OpenTracing\Tests;

use DateTime;
use OpenTracing\InvalidSpanOptionException;
use OpenTracing\NoopSpanContext;
use OpenTracing\Reference;
use OpenTracing\StartSpanOptions;
use PHPUnit\Framework\TestCase;

/**
 * @covers StartSpanOptions
 */
final class StartSpanOptionsTest extends TestCase
{
    const REFERENCE_TYPE = 'a_reference_type';

    public function testSpanOptionsCanNotBeCreatedDueToInvalidOption()
    {
        $this->expectException(InvalidSpanOptionException::class);

        StartSpanOptions::create([
            'unknown_option' => 'value'
        ]);
    }

    public function testSpanOptionsWithInvalidCloseOnFinishOption()
    {
        $this->expectException(InvalidSpanOptionException::class);

        StartSpanOptions::create([
            'finish_span_on_close' => 'value'
        ]);
    }

    public function testSpanOptionsCanNotBeCreatedBecauseInvalidStartTime()
    {
        $this->expectException(InvalidSpanOptionException::class);

        StartSpanOptions::create([
            'start_time' => 'abc'
        ]);
    }

    /** @dataProvider validStartTime */
    public function testSpanOptionsCanBeCreatedBecauseWithValidStartTime($startTime)
    {
        $spanOptions = StartSpanOptions::create([
            'start_time' => $startTime
        ]);

        $this->assertEquals($spanOptions->getStartTime(), $startTime);
    }

    public function validStartTime()
    {
        return [
            [new DateTime()],
            ['1499355363'],
            [1499355363],
            [1499355363.123456]
        ];
    }

    public function testSpanOptionsCanBeCreatedWithValidReference()
    {
        $context = new NoopSpanContext();

        $options = [
            'references' => new Reference(self::REFERENCE_TYPE, $context),
        ];

        $spanOptions = StartSpanOptions::create($options);
        $references = $spanOptions->getReferences()[0];

        $this->assertTrue($references->isType(self::REFERENCE_TYPE));
        $this->assertSame($context, $references->getSpanContext());
    }

    public function testSpanOptionsDefaultCloseOnFinishValue()
    {
        $options = StartSpanOptions::create([]);

        $this->assertTrue($options->shouldFinishSpanOnClose());
    }

    public function testSpanOptionsWithValidFinishSpanOnClose()
    {
        $options = StartSpanOptions::create([
            'finish_span_on_close' => false,
        ]);

        $this->assertFalse($options->shouldFinishSpanOnClose());
    }

    public function testSpanOptionsAddsANewReference()
    {
        $context1 = new NoopSpanContext();
        $spanOptions = StartSpanOptions::create([
            'child_of' => $context1,
        ]);
        $this->assertCount(1, $spanOptions->getReferences());

        $context2 = new NoopSpanContext();
        $spanOptions = $spanOptions->withParent($context2);

        $this->assertCount(1, $spanOptions->getReferences());
        $this->assertSame($context2, $spanOptions->getReferences()[0]->getSpanContext());
    }

    public function testDefaultIgnoreActiveSpan()
    {
        $options = StartSpanOptions::create([]);

        $this->assertFalse($options->shouldIgnoreActiveSpan());
    }

    public function testSpanOptionsWithValidIgnoreActiveSpan()
    {
        $options = StartSpanOptions::create([
            'ignore_active_span' => true,
        ]);

        $this->assertTrue($options->shouldIgnoreActiveSpan());
    }
}
