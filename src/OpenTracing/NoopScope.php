<?php

namespace OpenTracing;

final class NoopScope implements Scope
{
    /**
     * @return NoopScope
     */
    public static function create(): Scope
    {
        return new self();
    }

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
        return NoopSpan::create();
    }
}
