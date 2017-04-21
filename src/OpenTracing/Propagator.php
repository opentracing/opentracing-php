<?php

namespace OpenTracing;

use OpenTracing\Propagators\TextMapReader;
use OpenTracing\Propagators\TextMapWriter;

interface Propagator
{
    const HTTP_HEADERS = 'http_headers';
    const TEXT_MAP = 'text_map';

    public function inject(Span $span, TextMapWriter $carrier);

    /** @return SpanContext */
    public function extract(TextMapReader $carrier);
}
