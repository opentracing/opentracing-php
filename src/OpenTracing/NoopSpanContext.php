<?php

namespace OpenTracing;

use EmptyIterator;

final class NoopSpanContext implements SpanContext
{
    public static function create()
    {
        return new self();
    }

    public function getIterator()
    {
        return new EmptyIterator();
    }

    public function getBaggageItem($key)
    {
        return null;
    }

    public function withBaggageItem($key, $value)
    {
        return new self();
    }
}
