<?php

declare(strict_types=1);

namespace OpenTracing;

use InvalidArgumentException;

/**
 * Thrown when passing an invalid option on Span creation
 */
final class InvalidSpanOptionException extends InvalidArgumentException
{
    /**
     * @return InvalidSpanOptionException
     */
    public static function forIncludingBothChildOfAndReferences(): InvalidSpanOptionException
    {
        return new self('Either "childOf" or "references" options are accepted but not both.');
    }

    /**
     * @param mixed $reference
     * @return InvalidSpanOptionException
     */
    public static function forInvalidReference($reference): InvalidSpanOptionException
    {
        return new self(sprintf(
            'Invalid reference. Expected OpenTracing\Reference, got %s.',
            is_object($reference) ? get_class($reference) : gettype($reference)
        ));
    }

    /**
     * @return InvalidSpanOptionException
     */
    public static function forInvalidStartTime(): InvalidSpanOptionException
    {
        return new self('Invalid start_time option. Expected int or float got string.');
    }

    /**
     * @param mixed $childOfOption
     * @return InvalidSpanOptionException
     */
    public static function forInvalidChildOf($childOfOption): InvalidSpanOptionException
    {
        return new self(sprintf(
            'Invalid child_of option. Expected Span or SpanContext, got %s',
            is_object($childOfOption) ? get_class($childOfOption) : gettype($childOfOption)
        ));
    }

    /**
     * @param string $key
     * @return InvalidSpanOptionException
     */
    public static function forUnknownOption(string $key): InvalidSpanOptionException
    {
        return new self(sprintf('Invalid option %s.', $key));
    }

    /**
     * @param mixed $tag
     * @return InvalidSpanOptionException
     */
    public static function forInvalidTag($tag): InvalidSpanOptionException
    {
        return new self(sprintf('Invalid tag. Expected string, got %s', gettype($tag)));
    }

    /**
     * @param mixed $tagValue
     * @return InvalidSpanOptionException
     */
    public static function forInvalidTagValue($tagValue): InvalidSpanOptionException
    {
        return new self(sprintf(
            'Invalid tag value. Expected scalar or object with __toString method, got %s',
            is_object($tagValue) ? get_class($tagValue) : gettype($tagValue)
        ));
    }

    /**
     * @param mixed $value
     * @return InvalidSpanOptionException
     */
    public static function forInvalidTags($value): InvalidSpanOptionException
    {
        return new self(sprintf(
            'Invalid tags value. Expected a associative array of tags, got %s',
            is_object($value) ? get_class($value) : gettype($value)
        ));
    }

    /**
     * @param mixed $value
     * @return InvalidSpanOptionException
     */
    public static function forInvalidReferenceSet($value): InvalidSpanOptionException
    {
        return new self(sprintf(
            'Invalid references set. Expected Reference or Reference[], got %s',
            is_object($value) ? get_class($value) : gettype($value)
        ));
    }

    /**
     * @param mixed $value
     * @return InvalidSpanOptionException
     */
    public static function forFinishSpanOnClose($value): InvalidSpanOptionException
    {
        return new self(sprintf(
            'Invalid type for finish_span_on_close. Expected bool, got %s',
            is_object($value) ? get_class($value) : gettype($value)
        ));
    }

    /**
     * @param mixed $value
     * @return InvalidSpanOptionException
     */
    public static function forIgnoreActiveSpan($value): InvalidSpanOptionException
    {
        return new self(sprintf(
            'Invalid type for ignore_active_span. Expected bool, got %s',
            is_object($value) ? get_class($value) : gettype($value)
        ));
    }
}
