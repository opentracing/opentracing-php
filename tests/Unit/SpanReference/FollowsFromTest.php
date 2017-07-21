<?php

namespace OpenTracingTests\Unit\SpanReference;

use OpenTracing\SpanContext;
use OpenTracing\SpanReference\ChildOf;
use OpenTracing\SpanReference\FollowsFrom;
use PHPUnit_Framework_TestCase;

/**
 * @covers FollowsFrom
 */
final class FollowsFromTest extends PHPUnit_Framework_TestCase
{
    public function testCreateFollowsFromReferenceSuccess()
    {
        $context = $this->createMock(SpanContext::class);
        $childOfReference = FollowsFrom::fromContext($context);

        $this->assertTrue($childOfReference->isTypeFollowsFrom());
        $this->assertFalse($childOfReference->isTypeChildOf());
    }
}
