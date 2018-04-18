<?php

namespace OpenTracing;

final class NoopScopeManager implements ScopeManager
{
    /**
     * {@inheritdoc}
     */
    public function activate(Span $span, $finishSpanOnClose = ScopeManager::DEFAULT_FINISH_SPAN_ON_CLOSE)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getActive()
    {
        return new NoopScope();
    }
}
