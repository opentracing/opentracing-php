<?php

namespace OpenTracing;

use EmptyIterator;

final class NoopSpanContext implements SpanContext
{
    /**
     * @return SpanContext
     */
    public static function create(): SpanContext
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
    public function getBaggageItem(string $key): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function withBaggageItem(string $key, string $value): ?SpanContext
    {
        return new self();
    }
}
