<?php

namespace OpenTracing\Exceptions;

use InvalidArgumentException;

final class InvalidSpanOption extends InvalidArgumentException
{
    public static function create($key)
    {
        return new self(sprintf('Invalid option value for %s', $key));
    }
}
