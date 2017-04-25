<?php

namespace OpenTracing\Exceptions;

use InvalidArgumentException;

/**
 * Thrown when the `format` passed to Tracer::Inject() or Tracer::extract() is not recognized
 * by the Tracer implementation.
 */
final class UnsupportedFormat extends InvalidArgumentException
{
    public static function withFormat($format)
    {
        return new self(sprintf('The format %s is not supported.', $format));
    }
}
