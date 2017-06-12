<?php

namespace OpenTracing;

use OpenTracing\Propagators\TextMapReader;
use OpenTracing\Propagators\TextMapWriter;
use TracingContext\TracingContext;

final class NoopTracer implements Tracer
{
    public static function create()
    {
        return new self();
    }

    public function startSpan(
        $operationName,
        SpanReference $parentReference = null,
        $startTimestamp = null,
        Tag ...$tags
    ) {
        return NoopSpan::create();
    }

    public function inject(SpanContext $spanContext, $format, TextMapWriter $carrier)
    {
    }

    public function extract($format, TextMapReader $carrier)
    {
        return SpanContext::createAsDefault();
    }
}
