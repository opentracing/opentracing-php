<?php

namespace OpenTracing;

use OpenTracing\Exceptions\InvalidReferencesSet;
use OpenTracing\Exceptions\InvalidSpanOption;
use OpenTracing\Exceptions\SpanContextNotFound;
use OpenTracing\Exceptions\UnsupportedFormat;

interface Tracer
{
    /**
     * @return ScopeManager the current {@link ScopeManager}, which may be a noop but may not be null.
     */
    public function getScopeManager();

    /**
     * @return Span the active {@link Span}. This is a shorthand for Tracer::getScopeManager()->getActive()->getSpan(),
     * and null will be returned if {@link Scope#active()} is null.
     */
    public function getActiveSpan();

    /**
     * Starts and returns a new `Span` representing a unit of work.
     *
     * This method differs from `startSpan` because it uses in-process
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
     * @param array|SpanOptions $options A set of optional parameters:
     *   - Zero or more references to related SpanContexts, including a shorthand for ChildOf and
     *     FollowsFrom reference types if possible.
     *   - An optional explicit start timestamp; if omitted, the current walltime is used by default
     *     The default value should be set by the vendor.
     *   - Zero or more tags
     * @param bool $finishSpanOnClose whether span should automatically be finished when {@link Scope#close()} is called
     * @return Scope
     */
    public function startActiveSpan($operationName, $finishSpanOnClose = true, $options = []);

    /**
     * Starts and returns a new `Span` representing a unit of work.
     *
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
     * @param $carrier
     *
     * @see Formats
     *
     * @throws UnsupportedFormat when the format is not recognized by the tracer
     * implementation
     */
    public function inject(SpanContext $spanContext, $format, &$carrier);

    /**
     * @param string $format
     * @param $carrier
     * @return SpanContext
     *
     * @see Formats
     *
     * @throws SpanContextNotFound when a context could not be extracted from carrier
     * @throws UnsupportedFormat when the format is not recognized by the tracer
     * implementation
     */
    public function extract($format, $carrier);

    /**
     * Allow tracer to send span data to be instrumented.
     *
     * This method might not be needed depending on the tracing implementation
     * but one should make sure this method is called after the request is finished.
     * As an implementor, a good idea would be to use register_shutdown_function
     * or fastcgi_finish_request in order to not to delay the end of the request to the client.
     *
     * @see register_shutdown_function()
     * @see fastcgi_finish_request()
     */
    public function flush();
}
