<?php

namespace OpenTracing;

final class NoopSpan implements Span
{
    /**
     * @return Span
     */
    public static function create(): Span
    {
        return new self();
    }

    /**
     * {@inheritdoc}
     */
    public function getOperationName(): string
    {
        return 'noop_span';
    }

    /**
     * {@inheritdoc}
     */
    public function getContext(): SpanContext
    {
        return NoopSpanContext::create();
    }

    /**
     * {@inheritdoc}
     */
    public function finish($finishTime = null): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function overwriteOperationName(string $newOperationName): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function setTag(string $key, $value): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function log(array $fields = [], $timestamp = null): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function addBaggageItem(string $key, string $value): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getBaggageItem(string $key): ?string
    {
        return null;
    }
}
