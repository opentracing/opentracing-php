<?php

namespace OpenTracing;

use TracingContext\TracingContext;

final class SpanContext
{
    /**
     * @var array
     */
    private $baggageItems;

    /**
     * @var Context
     */
    private $context;

    private function __construct(Context $context)
    {
        $this->context = $context;
    }

    public static function create(Context $context)
    {
        return new self($context);
    }

    /**
     * Creates a default SpanContext instance.
     *
     * @return SpanContext
     */
    public static function createAsDefault()
    {
        return new self(Context::createAsDefault());
    }

    /**
     * Iterates over the baggage items.
     *
     * @param callable $closure
     * @return array
     */
    public function foreachBaggageItem(callable $closure)
    {
        return array_map($closure, $this->baggageItems);
    }

    /**
     * @return TracingContext
     */
    public function context()
    {
        return $this->context->context();
    }
}
