<?php

namespace OpenTracing\Exceptions;

use DomainException;
use OpenTracing\Span;

/**
 * Thrown when a Span is finished and one of this operations is being performed:
 *
 * - Overwriting the operation name
 * - Finishing the span
 * - Set a tag for the span
 * - Log data in the span
 * - Set baggage items for the span
 */
final class SpanAlreadyFinished extends DomainException
{
    public static function create(Span $span)
    {
        return new self(sprintf('Span named %s is already finished.', $span->getOperationName()));
    }
}
