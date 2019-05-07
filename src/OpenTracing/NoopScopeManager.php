<?php

namespace OpenTracing;

final class NoopScopeManager implements ScopeManager
{
    /**
     * @return ScopeManager
     */
    public static function create(): ScopeManager
    {
        return new self();
    }

    /**
     * {@inheritdoc}
     */
    public function activate(Span $span, bool $finishSpanOnClose = ScopeManager::DEFAULT_FINISH_SPAN_ON_CLOSE): Scope
    {
        return NoopScope::create();
    }

    /**
     * {@inheritdoc}
     */
    public function getActive(): ?Scope
    {
        return NoopScope::create();
    }
}
