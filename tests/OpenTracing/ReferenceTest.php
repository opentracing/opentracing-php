<?php

namespace OpenTracing\Tests;

use OpenTracing\Exceptions\InvalidReferenceArgument;
use OpenTracing\NoopSpanContext;
use OpenTracing\Reference;
use PHPUnit\Framework\TestCase;
use TypeError;

/**
 * @covers Reference
 */
final class ReferenceTest extends TestCase
{
    const REFERENCE_TYPE = 'ref_type';

    public function testCreateAReferenceFailsOnInvalidContext()
    {
        $context = 'invalid_context';

        $this->expectException(TypeError::class);
        new Reference('child_of', $context);
    }

    public function testCreateAReferenceFailsOnEmptyType()
    {
        $context = new NoopSpanContext();

        $this->expectException(InvalidReferenceArgument::class);
        $this->expectExceptionMessage('Reference type can not be an empty string');
        new Reference('', $context);
    }

    public function testAReferenceCanBeCreatedAsACustomType()
    {
        $context = new NoopSpanContext();
        $reference = new Reference(self::REFERENCE_TYPE, $context);

        $this->assertSame($context, $reference->getSpanContext());
        $this->assertTrue($reference->isType(self::REFERENCE_TYPE));
    }
}
