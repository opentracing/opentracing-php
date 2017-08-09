<?php

namespace OpenTracingTests\Unit;

use OpenTracing\Exceptions\InvalidReferenceArgument;
use OpenTracing\NoopSpanContext;
use OpenTracing\SpanReference;
use PHPUnit_Framework_TestCase;

final class SpanReferenceTest extends PHPUnit_Framework_TestCase
{
    const REFERENCE_TYPE = 'ref_type';

    public function testCreateASpanReferenceFailsOnInvalidContext()
    {
        $context = 'invalid_context';
        $this->expectException(InvalidReferenceArgument::class);
        $this->expectExceptionMessage(
            'Reference expects \OpenTracing\Span or \OpenTracing\SpanContext as context, got string'
        );
        SpanReference::create('child_of', $context);
    }

    public function testCreateASpanReferenceFailsOnEmptyType()
    {
        $context = new NoopSpanContext();
        $this->expectException(InvalidReferenceArgument::class);
        $this->expectExceptionMessage('Reference type can not be an empty string');
        SpanReference::create('', $context);
    }

    public function testASpanReferenceCanBeCreatedAsACustomType()
    {
        $context = new NoopSpanContext();
        $reference = SpanReference::create(self::REFERENCE_TYPE, $context);
        $this->assertSame($context, $reference->getContext());
        $this->assertTrue($reference->isType(self::REFERENCE_TYPE));
    }
}
