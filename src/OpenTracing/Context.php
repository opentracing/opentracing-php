<?php

namespace OpenTracing;

use TracingContext\TracingContext;

final class Context
{
    private $context;

    private function __construct(TracingContext $context)
    {
        $this->context = $context;
    }

    public static function create(TracingContext $context)
    {
        return new self($context);
    }

    public function context()
    {
        return $this->context;
    }
}
