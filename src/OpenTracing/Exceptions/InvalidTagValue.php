<?php

namespace OpenTracing\Exceptions;

use InvalidArgumentException;

final class InvalidTagValue extends InvalidArgumentException
{
    public static function notScalar($value)
    {
        return new self(sprintf('Tag should be scalar, got %s.', gettype($value)));
    }

    public static function notStringable($value)
    {
        return new self(
            sprintf('Tag should be either scalar or an object implementing __toString, got %s.', get_class($value))
        );
    }
}
