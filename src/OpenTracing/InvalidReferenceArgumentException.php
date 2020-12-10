<?php

declare(strict_types=1);

namespace OpenTracing;

use InvalidArgumentException;

/**
 * Thrown when passing an invalid argument for a reference
 */
final class InvalidReferenceArgumentException extends InvalidArgumentException
{
    /**
     * @return InvalidReferenceArgumentException
     */
    public static function forEmptyType(): InvalidReferenceArgumentException
    {
        return new self('Reference type can not be an empty string');
    }

    /**
     * @param mixed $context
     * @return InvalidReferenceArgumentException
     */
    public static function forInvalidContext($context): InvalidReferenceArgumentException
    {
        return new self(sprintf(
            'Reference expects \OpenTracing\Span or \OpenTracing\SpanContext as context, got %s',
            is_object($context) ? get_class($context) : gettype($context)
        ));
    }
}
