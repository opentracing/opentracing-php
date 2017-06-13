<?php

namespace OpenTracing;

use OpenTracing\Propagators\TextMapReader;
use OpenTracing\Propagators\TextMapWriter;

interface Propagator
{
    const HTTP_HEADERS = 'http_headers';
    const TEXT_MAP = 'text_map';

    /**
     * @param Span $span
     * @param TextMapWriter $carrier
     */
    public function inject(Span $span, TextMapWriter $carrier);

    /**
     * @param TextMapReader $carrier
     * @return SpanContext
     */
    public function extract(TextMapReader $carrier);
}
