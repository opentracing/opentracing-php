<?php

namespace OpenTracing;

use OpenTracing\Exceptions\InvalidReferencesSet;
use OpenTracing\Exceptions\InvalidSpanOption;
use OpenTracing\Exceptions\SpanContextNotFound;
use OpenTracing\Exceptions\UnsupportedFormat;

interface Tracer
{
    /**
     * @param string $operationName
     * @param array|SpanOptions $options A set of optional parameters:
     *   - Zero or more references to related SpanContexts, including a shorthand for ChildOf and
     *     FollowsFrom reference types if possible.
     *   - An optional explicit start timestamp; if omitted, the current walltime is used by default
     *     The default value should be set by the vendor.
     *   - Zero or more tags
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
     * As an implementor, a good idea would be to use an asynchronous message bus
     * or use the call to fastcgi_finish_request in order to not to delay the end
     * of the request to the client.
     *
     * @see register_shutdown_function()
     * @see fastcgi_finish_request()
     */
    public function flush();
}
