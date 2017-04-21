<?php

namespace OpenTracing;

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

    public function foreachBaggageItem(callable $closure)
    {
        return array_map($closure, $this->baggageItems);
    }

    public function context()
    {
        return $this->context->context();
    }
}
