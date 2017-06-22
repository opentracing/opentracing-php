<?php

namespace OpenTracing\Exceptions;

use InvalidArgumentException;

final class InvalidTagArgument extends InvalidArgumentException
{
    public static function notScalarValue($value)
    {
        return new self(sprintf('Tag value should be scalar, got %s.', gettype($value)));
    }

    public static function notStringableValue($value)
    {
        return new self(
            sprintf(
                'Tag value should be either scalar or an object implementing __toString, got %s.',
                get_class($value)
            )
        );
    }

    public static function notStringKey($key)
    {
        return new self(sprintf('Tag key should be string, got %s.', gettype($key)));
    }
}
