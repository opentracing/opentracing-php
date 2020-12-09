<?php

declare(strict_types=1);

namespace OpenTracing\Mock;

use OpenTracing\InvalidReferenceArgumentException;
use OpenTracing\UnsupportedFormatException;
use OpenTracing\Scope;
use OpenTracing\ScopeManager;
use OpenTracing\Span;
use OpenTracing\SpanContext;
use OpenTracing\StartSpanOptions;
use OpenTracing\Tracer;

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
    public function startActiveSpan(string $operationName, $options = []): Scope
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
    public function startSpan(string $operationName, $options = []): Span
    {
        if (!($options instanceof StartSpanOptions)) {
            $options = StartSpanOptions::create($options);
        }

        if (empty($options->getReferences())) {
            $spanContext = MockSpanContext::createAsRoot();
        } else {
            $referenceContext = $options->getReferences()[0]->getSpanContext();
            if (!$referenceContext instanceof MockSpanContext) {
                throw InvalidReferenceArgumentException::forInvalidContext($referenceContext);
            }
            $spanContext = MockSpanContext::createAsChildOf($referenceContext);
        }

        $span = new MockSpan($operationName, $spanContext, $options->getStartTime());

        foreach ($options->getTags() as $key => $value) {
            $span->setTag($key, $value);
        }

        $this->spans[] = $span;

        return $span;
    }

    /**
     * {@inheritdoc}
     */
    public function inject(SpanContext $spanContext, string $format, &$carrier): void
    {
        if (!array_key_exists($format, $this->injectors)) {
            throw UnsupportedFormatException::forFormat($format);
        }

        $this->injectors[$format]($spanContext, $carrier);
    }

    /**
     * {@inheritdoc}
     */
    public function extract(string $format, $carrier): ?SpanContext
    {
        if (!array_key_exists($format, $this->extractors)) {
            throw UnsupportedFormatException::forFormat($format);
        }

        return $this->extractors[$format]($carrier);
    }

    /**
     * {@inheritdoc}
     */
    public function flush(): void
    {
        $this->spans = [];
    }

    /**
     * @return array|MockSpan[]
     */
    public function getSpans(): array
    {
        return $this->spans;
    }

    /**
     * {@inheritdoc}
     */
    public function getScopeManager(): ScopeManager
    {
        return $this->scopeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveSpan(): ?Span
    {
        if (null !== ($activeScope = $this->scopeManager->getActive())) {
            return $activeScope->getSpan();
        }

        return null;
    }
}
