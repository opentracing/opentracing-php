<?php

namespace OpenTracing;

use OpenTracing\Propagators\TextMapReader;
use OpenTracing\Propagators\TextMapWriter;

final class NoopTracer implements Tracer
{
    public static function create()
    {
        return new self();
    }

    /** @return Span */
    public function startSpan($operationName, SpanReference $parentReference = null, $startTimestamp = null, Tag ...$tags)
    {
        // TODO: Implement startSpan() method.
    }

    public function inject(SpanContext $spanContext, $format, TextMapWriter $carrier)
    {
        // TODO: Implement inject() method.
    }

    /** @return SpanContext */
    public function extract($format, TextMapReader $carrier)
    {
        // TODO: Implement extract() method.
    }
}
