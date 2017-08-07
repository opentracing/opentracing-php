<?php

namespace OpenTracing\Exceptions;

use InvalidArgumentException;

/**
 * Thrown when passing an invalid option on Span creation
 */
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

    public static function invalidTag($tag)
    {
        return new self(sprintf('Invalid tag. Expected string, got %s', $tag));
    }

    public static function invalidTagValue($tagValue)
    {
        return new self(sprintf(
            'Invalid tag value. Expected scalar or object with __toString method, got %s',
            is_object($tagValue) ? get_class($tagValue) : gettype($tagValue)
        ));
    }
}
