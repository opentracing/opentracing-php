<?php

namespace OpenTracing\SpanReference;

use OpenTracing\SpanContext;
use OpenTracing\SpanReference;

final class FollowsFrom implements SpanReference
{
    private $spanContext;

    private function __construct(SpanContext $spanContext)
    {
        $this->spanContext = $spanContext;
    }

    /**
     * @param SpanContext $spanContext
     * @return FollowsFrom
     */
    public static function fromContext(SpanContext $spanContext)
    {
        return new self($spanContext);
    }

    /**
     * @return bool
     */
    public function isTypeChildOf()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isTypeFollowsFrom()
    {
        return true;
    }

    /**
     * @return SpanContext
     */
    public function referencedContext()
    {
        return $this->spanContext;
    }
}
