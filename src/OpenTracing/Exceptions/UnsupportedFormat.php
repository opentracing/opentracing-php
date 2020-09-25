<?php

declare(strict_types=1);

namespace OpenTracing\Exceptions;

use UnexpectedValueException;

/**
 * Thrown when trying to inject or extract in an invalid format
 */
final class UnsupportedFormat extends UnexpectedValueException
{
    /**
     * @param string $format
     * @return UnsupportedFormat
     */
    public static function forFormat(string $format): UnsupportedFormat
    {
        return new self(sprintf('The format \'%s\' is not supported.', $format));
    }
}
