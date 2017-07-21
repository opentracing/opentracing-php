<?php

namespace OpenTracingTests\Unit;

use OpenTracing\Exceptions\InvalidSpanOption;
use OpenTracing\SpanOptions;
use PHPUnit_Framework_TestCase;

/**
 * @covers SpanOptions
 */
final class SpanOptionsTest extends PHPUnit_Framework_TestCase
{
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
}
