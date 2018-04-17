<?php

namespace OpenTracing\Mock\Tests;

use OpenTracing\Mock\MockScopeManager;
use OpenTracing\Mock\MockTracer;
use PHPUnit_Framework_TestCase;

final class MockScopeManagerTest extends PHPUnit_Framework_TestCase
{
    const OPERATION_NAME = 'test_name';

    public function testGetActiveFailsWithNoActiveSpans()
    {
        $scopeManager = new MockScopeManager();
        $this->assertNull($scopeManager->getActive());
    }

    public function testActivateSuccess()
    {
        $tracer = new MockTracer();
        $span = $tracer->startSpan(self::OPERATION_NAME);
        $scopeManager = new MockScopeManager();
        $scopeManager->activate($span);
        $this->assertSame($span, $scopeManager->getActive()->getSpan());
    }

    public function testGetScopeReturnsNull()
    {
        $tracer = new MockTracer();
        $tracer->startSpan(self::OPERATION_NAME);
        $scopeManager = new MockScopeManager();
        $this->assertNull($scopeManager->getActive());
    }

    public function testGetScopeSuccess()
    {
        $tracer = new MockTracer();
        $span = $tracer->startSpan(self::OPERATION_NAME);
        $scopeManager = new MockScopeManager();
        $scopeManager->activate($span);
        $scope = $scopeManager->getActive();
        $this->assertSame($span, $scope->getSpan());
    }

    public function testDeactivateSuccess()
    {
        $tracer = new MockTracer();
        $span = $tracer->startSpan(self::OPERATION_NAME);
        $scopeManager = new MockScopeManager();
        $scopeManager->activate($span);
        $scope = $scopeManager->getActive();
        $scopeManager->deactivate($scope);
        $this->assertNull($scopeManager->getActive());
    }
}
