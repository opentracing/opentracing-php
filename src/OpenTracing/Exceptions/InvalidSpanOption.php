<?php

namespace OpenTracing\Exceptions;

use InvalidArgumentException;

final class InvalidSpanOption extends InvalidArgumentException
{
    public static function includesBothChildOfAndReferences()
    {
        return new self('Either "childOf" or "references" options are accepted but not both.');
    }

    public static function invalidReference($reference)
    {
        return new self(sprintf(
            'Invalid reference. Expected OpenTracing\SpanReference, got %s.',
            is_object($reference) ? get_class($reference) : gettype($reference)
        ));
    }

    public static function invalidStartTime()
    {
        return new self(sprintf('Invalid start_time option. Expected int or float got string.'));
    }

    public static function invalidChildOf($childOfOption)
    {
        return new self(sprintf(
            'Invalid child_of option. Expected Span or SpanContext, got %s',
            is_object($childOfOption) ? get_class($childOfOption) : gettype($childOfOption)
        ));
    }

    public static function unknownOption($key)
    {
        return new self(sprintf('Invalid option %s.', $key));
    }
}
