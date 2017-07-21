<?php

namespace OpenTracingTests\Unit\SpanReference;

use OpenTracing\SpanContext;
use OpenTracing\SpanReference\ChildOf;
use PHPUnit_Framework_TestCase;

/**
 * @covers ChildOf
 */
final class ChildOfTest extends PHPUnit_Framework_TestCase
{
    public function testCreateChildOfReferenceSuccess()
    {
        $context = $this->createMock(SpanContext::class);
        $childOfReference = ChildOf::fromContext($context);

        $this->assertTrue($childOfReference->isTypeChildOf());
        $this->assertFalse($childOfReference->isTypeFollowsFrom());
    }
}
