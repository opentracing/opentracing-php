<?php

namespace OpenTracing\Tests;

use OpenTracing\Exceptions\InvalidSpanOption;
use OpenTracing\NoopSpanContext;
use OpenTracing\StartSpanOptions;
use OpenTracing\Reference;
use PHPUnit_Framework_TestCase;

/**
 * @covers StartSpanOptions
 */
final class StartSpanOptionsTest extends PHPUnit_Framework_TestCase
{
    const REFERENCE_TYPE = 'a_reference_type';

    public function testSpanOptionsCanNotBeCreatedDueToInvalidOption()
    {
        $this->expectException(InvalidSpanOption::class);

        new StartSpanOptions([
            'unknown_option' => 'value'
        ]);
    }

    public function testSpanOptionsWithInvalidCloseOnFinishOption()
    {
        $this->expectException(InvalidSpanOption::class);

        new StartSpanOptions([
            'finish_span_on_close' => 'value'
        ]);
    }

    public function testSpanOptionsCanNotBeCreatedBecauseInvalidStartTime()
    {
        $this->expectException(InvalidSpanOption::class);

        new StartSpanOptions([
            'start_time' => 'abc'
        ]);
    }

    /** @dataProvider validStartTime */
    public function testSpanOptionsCanBeCreatedBecauseWithValidStartTime($startTime)
    {
        $spanOptions = new StartSpanOptions([
            'start_time' => $startTime
        ]);

        $this->assertEquals($spanOptions->getStartTime(), $startTime);
    }

    public function validStartTime()
    {
        return [
            [new \DateTime()],
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

        $spanOptions = new StartSpanOptions($options);
        $references = $spanOptions->getReferences()[0];

        $this->assertTrue($references->isType(self::REFERENCE_TYPE));
        $this->assertSame($context, $references->getContext());
    }

    public function testSpanOptionsDefaultCloseOnFinishValue()
    {
        $options = new StartSpanOptions([]);

        $this->assertTrue($options->shouldFinishSpanOnClose());
    }

    public function testSpanOptionsWithValidFinishSpanOnClose()
    {
        $options = new StartSpanOptions([
            'finish_span_on_close' => false,
        ]);

        $this->assertFalse($options->shouldFinishSpanOnClose());
    }

    public function testSpanOptionsAddsANewReference()
    {
        $context1 = new NoopSpanContext();
        $spanOptions = new StartSpanOptions([
            'child_of' => $context1,
        ]);
        $this->assertCount(1, $spanOptions->getReferences());

        $context2 = new NoopSpanContext();
        $spanOptions = $spanOptions->withParent($context2);
        $this->assertCount(1, $spanOptions->getReferences());
        $this->assertSame($context2, $spanOptions->getReferences()[0]->getContext());
    }

    public function testDefaultIgnoreActiveSpan()
    {
        $options = new StartSpanOptions([]);

        $this->assertFalse($options->shouldIgnoreActiveSpan());
    }

    public function testSpanOptionsWithValidIgnoreActiveSpan()
    {
        $options = new StartSpanOptions([
            'ignore_active_span' => true,
        ]);

        $this->assertTrue($options->shouldIgnoreActiveSpan());
    }
}
