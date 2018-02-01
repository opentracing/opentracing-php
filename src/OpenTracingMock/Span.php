<?php

namespace OpenTracingMock;

use OpenTracing\Span as OTSpan;

final class Span implements OTSpan
{
    /**
     * @var string
     */
    private $operationName;

    /**
     * @var SpanContext
     */
    private $context;

    /**
     * @var array
     */
    private $tags;

    /**
     * @var array
     */
    private $logs;

    /**
     * @var int
     */
    private $startTime;

    /**
     * @var int|null
     */
    private $duration;

    private function __construct($operationName, SpanContext $context, $startTime)
    {
        $this->operationName = $operationName;
        $this->context = $context;
        $this->startTime = $startTime;
    }

    public static function create($operationName, SpanContext $context, $startTime = null)
    {
        return new self($operationName, $context, $startTime ?: time());
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
    public function finish($finishTime = null, array $logRecords = [])
    {
        $finishTime = ($finishTime ?: time());
        $this->log($logRecords, $finishTime);
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
    public function setTags(array $tags)
    {
        $this->tags = array_merge($this->tags, $tags);
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
