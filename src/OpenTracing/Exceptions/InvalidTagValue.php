<?php

namespace OpenTracing\Exceptions;

use InvalidArgumentException;

final class InvalidTagValue extends InvalidArgumentException
{
    public static function create($value)
    {
        return new self(sprintf('Tag should be scalar, got %s.', gettype($value)));
    }
}
