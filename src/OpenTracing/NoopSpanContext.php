<?php

declare(strict_types=1);

namespace OpenTracing;

use EmptyIterator;
use Traversable;

final class NoopSpanContext implements SpanContext
{
    /**
     * {@inheritdoc}
     */
    public function getIterator(): Traversable
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
    public function withBaggageItem(string $key, string $value): SpanContext
    {
        return new self();
    }
}
