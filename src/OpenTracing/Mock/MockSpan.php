<?php

declare(strict_types=1);

namespace OpenTracing\Mock;

use OpenTracing\Span;
use OpenTracing\SpanContext;

final class MockSpan implements Span
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
        string $operationName,
        SpanContext $context,
        ?int $startTime = null
    ) {
        $this->operationName = $operationName;
        $this->context = $context;
        $this->startTime = $startTime ?: time();
    }

    /**
     * {@inheritdoc}
     */
    public function getOperationName(): string
    {
        return $this->operationName;
    }

    /**
     * {@inheritdoc}
     * @return SpanContext|MockSpanContext
     */
    public function getContext(): SpanContext
    {
        return $this->context;
    }

    public function getStartTime(): ?int
    {
        return $this->startTime;
    }

    /**
     * {@inheritdoc}
     */
    public function finish($finishTime = null): void
    {
        $finishTime = ($finishTime ?: time());
        $this->duration = $finishTime - $this->startTime;
    }

    public function isFinished(): bool
    {
        return $this->duration !== null;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    /**
     * {@inheritdoc}
     */
    public function overwriteOperationName(string $newOperationName): void
    {
        $this->operationName = (string)$newOperationName;
    }

    /**
     * {@inheritdoc}
     */
    public function setTag(string $key, $value): void
    {
        $this->tags[$key] = $value;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * {@inheritdoc}
     */
    public function log(array $fields = [], $timestamp = null): void
    {
        $this->logs[] = [
            'timestamp' => $timestamp ?: time(),
            'fields' => $fields,
        ];
    }

    public function getLogs(): array
    {
        return $this->logs;
    }

    /**
     * {@inheritdoc}
     */
    public function addBaggageItem(string $key, string $value): void
    {
        $this->context = $this->context->withBaggageItem($key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getBaggageItem(string $key): ?string
    {
        return $this->context->getBaggageItem($key);
    }
}
