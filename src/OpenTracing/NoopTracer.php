<?php

namespace OpenTracing;

final class NoopTracer implements Tracer
{
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
    public function startActiveSpan($operationName, $options = [])
    {

        return NoopSpan::create();
    }

    /**
     * {@inheritdoc}
     */
    public function startManualSpan($operationName, $options = [])
    {
        return NoopSpan::create();
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
    public function makeActive(Span $span)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function makeInactive(Span $span)
    {
    }
}
