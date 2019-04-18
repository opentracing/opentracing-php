<?php

namespace OpenTracing;

final class NoopTracer implements Tracer
{
    /**
     * @return Tracer
     */
    public static function create(): Tracer
    {
        return new self();
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveSpan(): ?Span
    {
        return NoopSpan::create();
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
        return NoopSpan::create();
    }

    /**
     * {@inheritdoc}
     */
    public function startActiveSpan(string $operationName, $options = []): Scope
    {
        return NoopScope::create();
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
        return NoopSpanContext::create();
    }

    /**
     * {@inheritdoc}
     */
    public function flush(): void
    {
    }
}
