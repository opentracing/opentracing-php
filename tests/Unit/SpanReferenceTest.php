<?php

namespace OpenTracingTests\Unit;

use OpenTracing\Exceptions\InvalidReferenceArgument;
use OpenTracing\NoopSpanContext;
use OpenTracing\SpanReference;
use PHPUnit_Framework_TestCase;

final class SpanReferenceTest extends PHPUnit_Framework_TestCase
{
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

    public function testASpanReferenceCanBeCreatedAsChildOf()
    {
        $context = new NoopSpanContext();
        $reference = SpanReference::createAsChildOf($context);
        $this->assertSame($context, $reference->getContext());
        $this->assertTrue($reference->isType('child_of'));
    }

    public function testASpanReferenceCanBeCreatedAsFollowsFrom()
    {
        $context = new NoopSpanContext();
        $reference = SpanReference::createAsFollowsFrom($context);
        $this->assertSame($context, $reference->getContext());
        $this->assertTrue($reference->isType('follows_from'));
    }
}
