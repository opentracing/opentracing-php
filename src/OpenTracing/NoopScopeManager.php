<?php

declare(strict_types=1);

namespace OpenTracing;

final class NoopScopeManager implements ScopeManager
{
    /**
     * {@inheritdoc}
     */
    public function activate(Span $span, bool $finishSpanOnClose = ScopeManager::DEFAULT_FINISH_SPAN_ON_CLOSE): Scope
    {
        return new NoopScope();
    }

    /**
     * {@inheritdoc}
     */
    public function getActive(): ?Scope
    {
        return new NoopScope();
    }
}
