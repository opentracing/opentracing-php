<?php

namespace OpenTracing;

use OpenTracing\Carriers\HttpHeaders;
use OpenTracing\Carriers\TextMap;
use OpenTracing\Exceptions\InvalidSpanOption;
use OpenTracing\Exceptions\SpanContextNotFound;
use OpenTracing\Exceptions\UnsupportedFormat;
use OpenTracing\Propagators\Reader;
use OpenTracing\Propagators\Writer;

interface Tracer
{
    const FORMAT_BINARY = 1;

    /**
     * @see HttpHeaders
     */
    const FORMAT_TEXT_MAP = 2;

    /**
     * @see TextMap
     */
    const FORMAT_HTTP_HEADERS = 3;

    /**
     * @deprecated use either startActiveSpan or startManualSpan instead.
     * As implementor consider this as a backward compatibility alias for
     * startActiveSpan
     */
    public function startSpan($operationName, $options = []);

    /**
     * Starts and returns a new `Span` representing a unit of work.
     *
     * This method differs from `startManualSpan` because it uses in-process
     * context propagation to keep track of the current active `Span` (if
     * available).
     *
     * Starting a root `Span` with no casual references and a child `Span`
     * in a different function, is possible without passing the parent
     * reference around:
     *
     *  function handleRequest(Request $request, $userId)
     *  {
     *      $rootSpan = $this->tracer->startActiveSpan('request.handler');
     *      $user = $this->repository->getUser($userId);
     *  }
     *
     *  function getUser($userId)
     *  {
     *      // `$childSpan` has `$rootSpan` as parent.
     *      $childSpan = $this->tracer->startActiveSpan('db.query');
     *  }
     *
     * @param string $operationName
     * @param array|SpanOptions $options
     * @return Span
     * @throws InvalidSpanOption for invalid option
     */
    public function startActiveSpan($operationName, $options = []);

    /**
     * Starts and returns a new Span representing a unit of work.
     *
     * @param string $operationName
     * @param array|SpanOptions $options
     * @return Span
     * @throws InvalidSpanOption for invalid option
     */
    public function startManualSpan($operationName, $options = []);

    /**
     * @return ActiveSpanSource
     */
    public function activeSpanSource();

    /**
     * @return Span
     */
    public function activeSpan();

    /**
     * @param SpanContext $spanContext
     * @param int $format
     * @param Writer $carrier
     * @throws UnsupportedFormat when the format is not recognized by the tracer
     * implementation
     */
    public function inject(SpanContext $spanContext, $format, Writer $carrier);

    /**
     * @param int $format
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
