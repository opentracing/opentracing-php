<?php

namespace OpenTracing\Exceptions;

use DomainException;

/**
 * Thrown when the `carrier` passed to the Tracer::extract() is valid and
 * uncorrupted but has insufficient information to extract a SpanContext
 */
final class SpanContextNotFound extends DomainException
{
    /**
     * @return SpanContextNotFound
     */
    public static function create()
    {
        return new self('SpanContext not found in extract carrier');
    }
}
