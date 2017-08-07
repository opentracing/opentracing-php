<?php

namespace OpenTracing\Exceptions;

use UnexpectedValueException;

/**
 * Thrown when trying to inject or extract in an invalid format
 */
final class UnsupportedFormat extends UnexpectedValueException
{
    public static function create($format)
    {
        return new self(sprintf('The format %s is not supported.', $format));
    }
}
