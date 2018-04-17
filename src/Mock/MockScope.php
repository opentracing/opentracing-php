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

    /**
     * @var bool
     */
    private $finishSpanOnClose;

    /**
     * @param MockScopeManager $scopeManager
     * @param Span $span
     * @param bool $finishSpanOnClose
     */
    public function __construct(
        MockScopeManager $scopeManager,
        Span $span,
        $finishSpanOnClose
    ) {
        $this->scopeManager = $scopeManager;
        $this->span = $span;
        $this->finishSpanOnClose = $finishSpanOnClose;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        if ($this->finishSpanOnClose) {
            $this->span->finish();
        }
        
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
