<?php

namespace OpenTracing\Mock;

use OpenTracing\Scope;
use OpenTracing\Span;

final class MockScope implements Scope
{
    /**
     * @var Span
     */
    private $span;

    /**
     * @var MockScopeManager
     */
    private $scopeManager;

    public function __construct(MockScopeManager $scopeManager, Span $span)
    {
        $this->scopeManager = $scopeManager;
        $this->span = $span;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        $this->scopeManager->deactivate($this);
    }

    /**
     * {@inheritdoc}
     * @return Span|MockSpan
     */
    public function getSpan()
    {
        return $this->span;
    }
}
