<?php

namespace OpenTracing;

final class NoopScopeManager implements ScopeManager
{
    public static function create()
    {
        return new self();
    }

    /**
     * {@inheritdoc}
     */
    public function activate(Span $span)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getActive()
    {
        return NoopScope::create();
    }

    /**
     * {@inheritdoc}
     */
    public function getScope(Span $span)
    {
        return NoopScope::create();
    }
}
