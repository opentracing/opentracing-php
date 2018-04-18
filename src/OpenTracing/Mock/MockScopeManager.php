<?php

namespace OpenTracing\Mock;

use OpenTracing\Scope;
use OpenTracing\ScopeManager;
use OpenTracing\Span;

final class MockScopeManager implements ScopeManager
{
    /**
     * @var array|Scope[]
     */
    private $scopes = [];

    /**
     * {@inheritdoc}
     */
    public function activate(Span $span, $finishSpanOnClose = ScopeManager::DEFAULT_FINISH_SPAN_ON_CLOSE)
    {
        $scope = new MockScope($this, $span, $finishSpanOnClose);
        $this->scopes[] = $scope;
        return $scope;
    }

    /**
     * {@inheritdoc}
     */
    public function getActive()
    {
        if (empty($this->scopes)) {
            return null;
        }

        return $this->scopes[count($this->scopes) - 1];
    }

    public function deactivate(MockScope $scope)
    {
        $scopeLength = count($this->scopes);

        for ($i = 0; $i < $scopeLength; $i++) {
            if ($scope === $this->scopes[$i]) {
                unset($this->scopes[$i]);
            }
        }
    }
}
