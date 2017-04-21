<?php

namespace OpenTracing\Exceptions;

use InvalidArgumentException;

final class UnsupportedFormat extends InvalidArgumentException
{
    public static function withFormat($format)
    {
        return new self(sprintf('The format %s is not supported.', $format));
    }
}
