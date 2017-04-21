<?php

namespace OpenTracing;

use OpenTracing\Propagators\TextMapReader;
use OpenTracing\Propagators\TextMapWriter;

interface Tracer
{
    /** @return Span */
    public function startSpan($operationName, SpanReference $parentReference = null, $startTimestamp = null, Tag ...$tags);

    public function inject(SpanContext $spanContext, $format, TextMapWriter $carrier);

    /** @return SpanContext */
    public function extract($format, TextMapReader $carrier);
}
