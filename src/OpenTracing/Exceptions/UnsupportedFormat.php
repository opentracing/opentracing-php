<?php

namespace OpenTracing\Exceptions;

use UnexpectedValueException;

/**
 * Thrown when trying to inject or extract in an invalid format
 */
final class UnsupportedFormat extends UnexpectedValueException
{
    /**
     * @param int $format
     * @return UnsupportedFormat
     */
    public static function forFormat($format)
    {
        return new self(sprintf('The format %d is not supported.', $format));
    }
}
