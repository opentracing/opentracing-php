<?php

namespace OpenTracing\SpanReference;

use OpenTracing\SpanContext;
use OpenTracing\SpanReference;

final class ChildOf implements SpanReference
{
    private $spanContext;

    private function __construct(SpanContext $spanContext)
    {
        $this->spanContext = $spanContext;
    }

    public static function withContext(SpanContext $spanContext)
    {
        return new self($spanContext);
    }

    /** @return bool */
    public function isTypeChildOf()
    {
        return true;
    }

    /** @return bool */
    public function isTypeFollowsFrom()
    {
        return false;
    }

    /** @return SpanContext */
    public function referencedContext()
    {
        return $this->spanContext;
    }
}
