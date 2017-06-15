<?php

namespace OpenTracing;

use OpenTracing\Propagators\Reader;
use OpenTracing\Propagators\Writer;

final class NoopTracer implements Tracer
{
    public static function create()
    {
        return new self();
    }

    /**
     * {@inheritdoc}
     */
    public function startActiveSpan($operationName, $options = [])
    {
    
        return NoopSpan::create();
    }

    public function startManualSpan($operationName, $options = [])
    {
        return NoopSpan::create();
    }

    public function inject(SpanContext $spanContext, $format, Writer $carrier)
    {
    }

    public function extract($format, Reader $carrier)
    {
        return SpanContext::createAsDefault();
    }

    public function flush()
    {
    }

    public function activeSpanSource()
    {
        return NoopActiveSpanSource::create();
    }

    public function activeSpan()
    {
        return NoopSpan::create();
    }
}
