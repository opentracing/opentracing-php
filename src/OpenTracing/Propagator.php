<?php

namespace OpenTracing;

use OpenTracing\Propagators\Reader;
use OpenTracing\Propagators\Writer;

interface Propagator
{
    const HTTP_HEADERS = 'http_headers';
    const TEXT_MAP = 'text_map';

    /**
     * Injects the Span context into a carrier
     *
     * @param Span $span
     * @param Writer $carrier
     */
    public function inject(Span $span, Writer $carrier);

    /**
     * Extracts a SpanContext from the carrier
     *
     * @param Reader $carrier
     * @return SpanContext
     */
    public function extract(Reader $carrier);
}
