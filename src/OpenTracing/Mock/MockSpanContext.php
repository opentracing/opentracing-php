<?php

declare(strict_types=1);

namespace OpenTracing\Mock;

use OpenTracing\SpanContext;
use ArrayIterator;

final class MockSpanContext implements SpanContext
{
    /**
     * @var int
     */
    private $traceId;

    /**
     * @var int
     */
    private $spanId;

    /**
     * @var bool
     */
    private $isSampled;

    /**
     * @var array
     */
    private $items;

    private function __construct(int $traceId, int $spanId, bool $isSampled, array $items)
    {
        $this->traceId = $traceId;
        $this->spanId = $spanId;
        $this->isSampled = $isSampled;
        $this->items = $items;
    }

    public static function create(int $traceId, int $spanId, bool $sampled = true, array $items = []): SpanContext
    {
        return new self($traceId, $spanId, $sampled, $items);
    }

    public static function createAsRoot(bool $sampled = true, array $items = []): SpanContext
    {
        $traceId = $spanId = self::nextId();
        return new self($traceId, $spanId, $sampled, $items);
    }

    public static function createAsChildOf(MockSpanContext $spanContext): SpanContext
    {
        $spanId = self::nextId();
        return new self($spanContext->traceId, $spanId, $spanContext->isSampled, $spanContext->items);
    }

    public function getTraceId(): int
    {
        return $this->traceId;
    }

    public function getSpanId(): int
    {
        return $this->spanId;
    }

    public function isSampled(): bool
    {
        return $this->isSampled;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function getBaggageItem(string $key): ?string
    {
        return array_key_exists($key, $this->items) ? $this->items[$key] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function withBaggageItem(string $key, string $value): SpanContext
    {
        return new self($this->traceId, $this->spanId, $this->isSampled, array_merge($this->items, [$key => $value]));
    }

    private static function nextId(): int
    {
        return mt_rand(0, 99999);
    }
}
