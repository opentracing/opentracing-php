<?php

namespace OpenTracing;

/**
 * Keeps track of the current active `Span`.
 */
interface ScopeManager
{
    /**
     * Activates an `Span`, so that it is used as a parent when creating new spans.
     * The implementation must keep track of the active spans sequence, so
     * that previous spans can be resumed after a deactivation.
     *
     * @param Span $span the {@link Span} that should become the {@link #active()}
     *
     * Weather the span automatically closes when finish is called depends on
     * the span options set during the creation of the span. See
     * {@link SpanOptions#closeSpanOnFinish}
     *
     * @return Scope
     */
    public function activate(Span $span);

    /**
     * Return the currently active {@link Scope} which can be used to access the
     * currently active {@link Scope#getSpan()}.
     *
     * If there is an {@link Scope non-null scope}, its wrapped {@link Span} becomes an implicit parent
     * (as {@link References#CHILD_OF} reference) of any
     * newly-created {@link Span} at {@link Tracer.SpanBuilder#startActive(boolean)} or {@link SpanBuilder#start()}
     * time rather than at {@link Tracer#buildSpan(String)} time.
     *
     * @return \OpenTracing\Scope|null
     */
    public function getActive();

    /**
     * Access the scope of a given Span if available.
     *
     * @param Span $span
     *
     * @return \OpenTracing\Scope|null
     */
    public function getScope(Span $span);
}
