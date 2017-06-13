<?php

namespace OpenTracing;

use OpenTracing\Propagators\TextMapReader;
use OpenTracing\Propagators\TextMapWriter;

interface Tracer
{
    /**
     * @param string $operationName
     * @param SpanReference|null $parentReference
     * @param float|\DateTimeInterface|null $startTimestamp if passing a float it should
     * represent the number of seconds
     * @param Tag[] $tags
     * @return Span
     */
    public function startSpan(
        $operationName,
        SpanReference $parentReference = null,
        $startTimestamp = null,
        array $tags = []
    );

    /**
     * @param string $operationName
     * @param array|SpanOptions $options
     * @return Span
     */
    public function startSpanWithOptions($operationName, $options);

    /**
     * @param SpanContext $spanContext
     * @param string $format
     * @param TextMapWriter $carrier
     */
    public function inject(SpanContext $spanContext, $format, TextMapWriter $carrier);

    /**
     * @param string $format
     * @param TextMapReader $carrier
     * @return SpanContext
     */
    public function extract($format, TextMapReader $carrier);

    /**
     * Allow tracer to send span data to be instrumented.
     *
     * This method might not be needed depending on the tracing implementation
     * but one should make sure this method is called after the request is finished.
     * As an implementor, a good idea would be to use an asynchronous {@ see message bus}
     * or use the call to {@see fastcgi_finish_request} in order to not to delay the end
     * of the request to the final user.
     */
    public function flush();
}
