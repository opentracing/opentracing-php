<?php

namespace OpenTracing\Exceptions;

use OpenTracing\SpanReference;
use RuntimeException;

final class UnknownSpanReferenceType extends RuntimeException
{
    public static function create(SpanReference $parentReference)
    {
        return new self(sprintf('Unknown span reference type on %s.', get_class($parentReference)));
    }
}
