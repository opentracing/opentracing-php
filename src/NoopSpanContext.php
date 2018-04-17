<?php

namespace OpenTracing;

use EmptyIterator;

final class NoopSpanContext implements SpanContext
{
    public static function create()
    {
        return new self();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new EmptyIterator();
    }

    /**
     * {@inheritdoc}
     */
    public function getBaggageItem($key)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function withBaggageItem($key, $value)
    {
        return new self();
    }
}
