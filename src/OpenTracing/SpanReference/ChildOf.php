<?php

namespace OpenTracing\SpanReference;

use OpenTracing\SpanContext;
use OpenTracing\SpanReference;

final class ChildOf implements SpanReference
{
    use DefaultTypeChecker;

    /**
     * @var SpanContext
     */
    private $spanContext;

    private function __construct(SpanContext $spanContext)
    {
        $this->spanContext = $spanContext;
    }

    /**
     * @param SpanContext $spanContext
     * @return ChildOf
     */
    public static function withContext(SpanContext $spanContext)
    {
        return new self($spanContext);
    }

    /**
     * @return bool
     */
    public function isTypeChildOf()
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
