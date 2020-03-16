<?php

declare(strict_types=1);

namespace OpenTracing;

final class NoopScope implements Scope
{
    /**
     * {@inheritdoc}
     */
    public function close(): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getSpan(): Span
    {
        return new NoopSpan();
    }
}
