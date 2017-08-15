<?php

namespace OpenTracing;

use OpenTracing\Propagators\Reader;
use OpenTracing\Propagators\Writer;
use TracingContext\TracingContext;

final class NoopTracer implements Tracer
{
    public static function create()
    {
        return new self();
    }

    public function startSpan($operationName, $options)
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
}
