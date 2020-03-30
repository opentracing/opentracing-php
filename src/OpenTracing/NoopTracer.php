<?php

declare(strict_types=1);

namespace OpenTracing;

final class NoopTracer implements Tracer
{
    /**
     * {@inheritdoc}
     */
    public function getActiveSpan(): ?Span
    {
        return new NoopSpan();
    }

    /**
     * {@inheritdoc}
     */
    public function getScopeManager(): ScopeManager
    {
        return new NoopScopeManager();
    }

    /**
     * {@inheritdoc}
     */
    public function startSpan(string $operationName, $options = []): Span
    {
        return new NoopSpan();
    }

    /**
     * {@inheritdoc}
     */
    public function startActiveSpan(string $operationName, $options = []): Scope
    {
        return new NoopScope();
    }

    /**
     * {@inheritdoc}
     */
    public function inject(SpanContext $spanContext, string $format, &$carrier): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function extract(string $format, $carrier): ?SpanContext
    {
        return new NoopSpanContext();
    }

    /**
     * {@inheritdoc}
     */
    public function flush(): void
    {
    }
}
