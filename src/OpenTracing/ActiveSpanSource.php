<?php

namespace OpenTracing;

/**
 * Keeps track of the current active `Span`.
 */
interface ActiveSpanSource
{
    /**
     * Activates an `Span`, so that it is used as a parent when creating new spans.
     * The implementation must keep track of the active spans sequence, so
     * that previous spans can be resumed after a deactivation.
     *
     * @param Span $span
     */
    public function activate(Span $span);

    /**
     * Returns current active `Span`.
     *
     * @return Span
     */
    public function getActiveSpan();

    /**
     * Deactivate the given `Span`, restoring the previous active one.
     *
     * This method must take in consideration that a `Span` may be deactivated
     * when it's not really active. In that case, the current active stack
     * must not be changed (idempotency).
     *
     * Do not confuse it with the finish concept:
     *  - $tracer->getActiveSpanSource()->deactivate($span) the span is not active but still "running"
     *  - $span->finish() the span is finished and deactivated
     *
     * @param Span $span
     */
    public function deactivate(Span $span);
}
