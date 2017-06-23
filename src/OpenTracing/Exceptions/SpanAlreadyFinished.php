<?php

namespace OpenTracing\Exceptions;

use DomainException;
use OpenTracing\Span;

final class SpanAlreadyFinished extends DomainException
{
    public static function create(Span $span)
    {
        return new self(sprintf('Span named %s is already finished.', $span->getOperationName()));
    }
}
