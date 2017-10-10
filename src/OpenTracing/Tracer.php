<?php

namespace OpenTracing;

use OpenTracing\Carriers\HttpHeaders;
use OpenTracing\Carriers\TextMap;
use OpenTracing\Exceptions\InvalidReferencesSet;
use OpenTracing\Exceptions\InvalidSpanOption;
use OpenTracing\Exceptions\SpanContextNotFound;
use OpenTracing\Exceptions\UnsupportedFormat;
use OpenTracing\Propagators\Reader;
use OpenTracing\Propagators\Writer;

interface Tracer
{
    const FORMAT_BINARY = 'binary';

    /**
     * @see HttpHeaders
     */
    const FORMAT_TEXT_MAP = 'text_map';

    /**
     * @see TextMap
     */
    const FORMAT_HTTP_HEADERS = 'http_headers';

    /**
     * @param string $operationName
     * @param array|SpanOptions $options
     * @return Span
     * @throws InvalidSpanOption for invalid option
     * @throws InvalidReferencesSet for invalid references set
     */
    public function startSpan($operationName, $options = []);

    /**
     * @param SpanContext $spanContext
     * @param string $format
     * @param Writer $carrier
     * @throws UnsupportedFormat when the format is not recognized by the tracer
     * implementation
     */
    public function inject(SpanContext $spanContext, $format, Writer $carrier);

    /**
     * @param string $format
     * @param Reader $carrier
     * @return SpanContext
     * @throws SpanContextNotFound when a context could not be extracted from Reader
     * @throws UnsupportedFormat when the format is not recognized by the tracer
     * implementation
     */
    public function extract($format, Reader $carrier);

    /**
     * Allow tracer to send span data to be instrumented.
     *
     * This method might not be needed depending on the tracing implementation
     * but one should make sure this method is called after the request is finished.
     * As an implementor, a good idea would be to use an asynchronous message bus
     * or use the call to fastcgi_finish_request in order to not to delay the end
     * of the request to the client.
     *
     * @see fastcgi_finish_request()
     * @see https://www.google.com/search?q=message+bus+php
     */
    public function flush();
}
