<?php

namespace OpenTracingTests\Unit;

use OpenTracing\Exceptions\InvalidSpanOption;
use OpenTracing\NoopSpanContext;
use OpenTracing\SpanOptions;
use OpenTracing\Reference;
use PHPUnit_Framework_TestCase;

/**
 * @covers SpanOptions
 */
final class SpanOptionsTest extends PHPUnit_Framework_TestCase
{
    const REFERENCE_TYPE = 'a_reference_type';

    public function testSpanOptionsCanNotBeCreatedDueToInvalidOption()
    {
        $this->expectException(InvalidSpanOption::class);

        SpanOptions::create([
            'unknown_option' => 'value'
        ]);
    }

    public function testSpanOptionsCanNotBeCreatedBecauseInvalidStartTime()
    {
        $this->expectException(InvalidSpanOption::class);

        SpanOptions::create([
            'start_time' => 'abc'
        ]);
    }

    /** @dataProvider validStartTime */
    public function testSpanOptionsCanBeCreatedBecauseWithValidStartTime($startTime)
    {
        $spanOptions = SpanOptions::create([
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
        $context = NoopSpanContext::create();

        $options = [
            'references' => Reference::create(self::REFERENCE_TYPE, $context),
        ];

        $spanOptions = SpanOptions::create($options);
        $references = $spanOptions->getReferences()[0];

        $this->assertTrue($references->isType(self::REFERENCE_TYPE));
        $this->assertSame($context, $references->getContext());
    }
}
