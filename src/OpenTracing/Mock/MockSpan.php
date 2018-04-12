<?php

namespace OpenTracing\Mock;

use OpenTracing\ScopeManager;
use OpenTracing\Span;
use OpenTracing\SpanContext;

final class MockSpan implements Span
{
    /**
     * @var string
     */
    private $operationName;

    /**
     * @var MockSpanContext
     */
    private $context;

    /**
     * @var array
     */
    private $tags = [];

    /**
     * @var array
     */
    private $logs = [];

    /**
     * @var int
     */
    private $startTime;

    /**
     * @var int|null
     */
    private $duration;

    public function __construct(
        $operationName,
        MockSpanContext $context,
        $startTime = null
    ) {
        $this->operationName = $operationName;
        $this->context = $context;
        $this->startTime = $startTime ?: time();
    }

    /**
     * {@inheritdoc}
     */
    public function getOperationName()
    {
        return $this->operationName;
    }

    /**
     * {@inheritdoc}
     * @return SpanContext|MockSpanContext
     */
    public function getContext()
    {
        return $this->context;
    }

    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * {@inheritdoc}
     */
    public function finish($finishTime = null)
    {
        $finishTime = ($finishTime ?: time());
        $this->duration = $finishTime - $this->startTime;
    }

    public function isFinished()
    {
        return $this->duration !== null;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * {@inheritdoc}
     */
    public function overwriteOperationName($newOperationName)
    {
        $this->operationName = (string) $newOperationName;
    }

    /**
     * {@inheritdoc}
     */
    public function setTag($key, $value)
    {
        $this->tags[$key] = $value;
    }

    public function getTags()
    {
        return $this->tags;
    }

    /**
     * {@inheritdoc}
     */
    public function log(array $fields = [], $timestamp = null)
    {
        $this->logs[] = [
            'timestamp' => $timestamp ?: time(),
            'fields' => $fields,
        ];
    }

    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * {@inheritdoc}
     */
    public function addBaggageItem($key, $value)
    {
        $this->context = $this->context->withBaggageItem($key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getBaggageItem($key)
    {
        return $this->context->getBaggageItem($key);
    }
}
