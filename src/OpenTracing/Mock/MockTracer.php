<?php

namespace OpenTracing\Mock;

use OpenTracing\Exceptions\UnsupportedFormat;
use OpenTracing\ScopeManager;
use OpenTracing\StartSpanOptions;
use OpenTracing\Tracer;
use OpenTracing\SpanContext;

final class MockTracer implements Tracer
{
    /**
     * @var array|MockSpan[]
     */
    private $spans = [];

    /**
     * @var array|callable[]
     */
    private $injectors;

    /**
     * @var array|callable[]
     */
    private $extractors;

    /**
     * @var ScopeManager
     */
    private $scopeManager;

    public function __construct(array $injectors = [], array $extractors = [])
    {
        $this->injectors = $injectors;
        $this->extractors = $extractors;
        $this->scopeManager = new MockScopeManager();
    }

    /**
     * {@inheritdoc}
     */
    public function startActiveSpan($operationName, $options = [])
    {
        if (!($options instanceof StartSpanOptions)) {
            $options = StartSpanOptions::create($options);
        }

        if (($activeSpan = $this->getActiveSpan()) !== null) {
            $options = $options->withParent($activeSpan);
        }

        $span = $this->startSpan($operationName, $options);

        return $this->scopeManager->activate($span, $options->shouldFinishSpanOnClose());
    }

    /**
     * {@inheritdoc}
     */
    public function startSpan($operationName, $options = [])
    {
        if (!($options instanceof StartSpanOptions)) {
            $options = StartSpanOptions::create($options);
        }

        if (empty($options->getReferences())) {
            $spanContext = MockSpanContext::createAsRoot();
        } else {
            $spanContext = MockSpanContext::createAsChildOf($options->getReferences()[0]);
        }

        $span = new MockSpan(
            $operationName,
            $spanContext,
            $options->getStartTime()
        );

        foreach ($options->getTags() as $key => $value) {
            $span->setTag($key, $value);
        }

        $this->spans[] = $span;

        return $span;
    }

    /**
     * {@inheritdoc}
     */
    public function inject(SpanContext $spanContext, $format, &$carrier)
    {
        if (!array_key_exists($format, $this->injectors)) {
            throw UnsupportedFormat::forFormat($format);
        }

        call_user_func($this->injectors[$format], $spanContext, $carrier);
    }

    /**
     * {@inheritdoc}
     */
    public function extract($format, $carrier)
    {
        if (!array_key_exists($format, $this->extractors)) {
            throw UnsupportedFormat::forFormat($format);
        }

        return call_user_func($this->extractors[$format], $carrier);
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        $this->spans = [];
    }

    /**
     * @return array|MockSpan[]
     */
    public function getSpans()
    {
        return $this->spans;
    }

    /**
     * {@inheritdoc}
     */
    public function getScopeManager()
    {
        return $this->scopeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveSpan()
    {
        if (null !== ($activeScope = $this->scopeManager->getActive())) {
            return $activeScope->getSpan();
        }

        return null;
    }
}
