<?php

declare(strict_types=1);

namespace OpenTracing;

use UnexpectedValueException;

/**
 * Thrown when trying to inject or extract in an invalid format
 */
final class UnsupportedFormatException extends UnexpectedValueException
{
    /**
     * @param string $format
     * @return UnsupportedFormatException
     */
    public static function forFormat(string $format): UnsupportedFormatException
    {
        return new self(sprintf('The format "%s" is not supported.', $format));
    }
}
