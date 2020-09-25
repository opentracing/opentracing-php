<?php

declare(strict_types=1);

namespace OpenTracing;

use OpenTracing\Exceptions\InvalidReferencesSetException;
use OpenTracing\Exceptions\InvalidSpanOptionException;
use OpenTracing\Exceptions\UnsupportedFormatException;

interface Tracer
{
    /**
     * Returns the current {@link ScopeManager}, which may be a noop but may not be null.
     *
     * @return ScopeManager
     */
    public function getScopeManager(): ScopeManager;

    /**
     * Returns the active {@link Span}. This is a shorthand for
     * Tracer::getScopeManager()->getActive()->getSpan(),
     * and null will be returned if {@link Scope#active()} is null.
     *
     * @return Span|null
     */
    public function getActiveSpan(): ?Span;

    /**
     * Starts a new span that is activated on a scope manager.
     *
     * It's also possible to not finish the {@see \OpenTracing\Span} when
     * {@see \OpenTracing\Scope} context expires:
     *
     *     $scope = $tracer->startActiveSpan('...', [
     *         'finish_span_on_close' => false,
     *     ]);
     *     $span = $scope->getSpan();
     *     try {
     *         $span->setTag(Tags\HTTP_METHOD, 'GET');
     *         // ...
     *     } finally {
     *         $scope->close();
     *     }
     *     // $span->finish() is not called as part of Scope deactivation as
     *     // finish_span_on_close is false
     *
     * @param string $operationName
     * @param array|StartSpanOptions $options Same as for startSpan() with
     *     additional option of `finish_span_on_close` that enables finishing
     *     of span whenever a scope is closed. It is true by default.
     *
     * @return Scope A Scope that holds newly created Span and is activated on
     *               a ScopeManager.
     */
    public function startActiveSpan(string $operationName, $options = []): Scope;

    /**
     * Starts and returns a new span representing a unit of work.
     *
     * Whenever `child_of` reference is not passed then
     * {@see \OpenTracing\ScopeManager::getActive()} span is used as `child_of`
     * reference. In order to ignore implicit parent span pass in
     * `ignore_active_span` option set to true.
     *
     * Starting a span with explicit parent:
     *
     *     $tracer->startSpan('...', [
     *         'child_of' => $parentSpan,
     *     ]);
     *
     * @param string $operationName
     * @param array|StartSpanOptions $options See StartSpanOptions for
     *                                        available options.
     *
     * @return Span
     *
     * @throws InvalidSpanOptionException for invalid option
     * @throws InvalidReferencesSetException for invalid references set
     * @see \OpenTracing\StartSpanOptions
     */
    public function startSpan(string $operationName, $options = []): Span;

    /**
     * @param SpanContext $spanContext
     * @param string $format
     * @param mixed $carrier
     * @return void
     *
     * @throws UnsupportedFormatException when the format is not recognized by the tracer
     * implementation
     * @see Formats
     */
    public function inject(SpanContext $spanContext, string $format, &$carrier): void;

    /**
     * @param string $format
     * @param mixed $carrier
     * @return SpanContext|null
     *
     * @throws UnsupportedFormatException when the format is not recognized by the tracer
     * implementation
     * @see Formats
     */
    public function extract(string $format, $carrier): ?SpanContext;

    /**
     * Allow tracer to send span data to be instrumented.
     *
     * This method might not be needed depending on the tracing implementation
     * but one should make sure this method is called after the request is delivered
     * to the client.
     *
     * As an implementor, a good idea would be to use {@see register_shutdown_function}
     * or {@see fastcgi_finish_request} in order to not to delay the end of the request
     * to the client.
     */
    public function flush(): void;
}
