<?php

namespace OpenTracing;

final class NoopTracer implements Tracer
{
    /**
     * {@inheritdoc}
     */
    public function getActiveSpan()
    {
        return NoopSpan::create();
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

        return NoopScope::create();
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
        return NoopSpanContext::create();
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
    }
}
