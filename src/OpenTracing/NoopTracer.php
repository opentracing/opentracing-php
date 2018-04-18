<?php

namespace OpenTracing;

final class NoopTracer implements Tracer
{
    /**
     * {@inheritdoc}
     */
    public function getActiveSpan()
    {
        return new NoopSpan();
    }

    /**
     * {@inheritdoc}
     */
    public function getScopeManager()
    {
        return new NoopScopeManager();
    }

    public static function create()
    {
        return new self();
    }

    /**
     * {@inheritdoc}
     */
    public function startSpan($operationName, $options = [])
    {
    }

    /**
     * {@inheritdoc}
     */
    public function startActiveSpan($operationName, $finishSpanOnClose = true, $options = [])
    {

        return new NoopScope();
    }

    /**
     * {@inheritdoc}
     */
    public function inject(SpanContext $spanContext, $format, &$carrier)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function extract($format, $carrier)
    {
        return new NoopSpanContext();
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
    }
}
