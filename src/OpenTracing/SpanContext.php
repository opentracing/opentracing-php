<?php

namespace OpenTracing;

use TracingContext\TracingContext;

final class SpanContext
{
    private $baggageItems;
    private $context;

    private function __construct(Context $context)
    {
        $this->context = $context;
    }

    public static function create(Context $context)
    {
        return new self($context);
    }

    public static function createAsDefault()
    {
        return new self(Context::createAsDefault());
    }

    public function foreachBaggageItem(callable $closure)
    {
        return array_map($closure, $this->baggageItems);
    }

    /** @return TracingContext */
    public function context()
    {
        return $this->context->context();
    }
}
