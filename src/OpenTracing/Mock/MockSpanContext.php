<?php

namespace OpenTracing\Mock;

use ArrayIterator;
use OpenTracing\SpanContext;

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

    private function __construct($traceId, $spanId, $isSampled, array $items)
    {
        $this->traceId = $traceId;
        $this->spanId = $spanId;
        $this->isSampled = $isSampled;
        $this->items = $items;
    }

    public static function create($traceId, $spanId, $sampled = true, array $items = [])
    {
        return new self($traceId, $spanId, $sampled, $items);
    }

    public static function createAsRoot($sampled = true, array $items = [])
    {
        $traceId = $spanId = self::nextId();
        return new self($traceId, $spanId, $sampled, $items);
    }

    public static function createAsChildOf(MockSpanContext $spanContext)
    {
        $spanId = self::nextId();
        return new self($spanContext->traceId, $spanId, $spanContext->isSampled, $spanContext->items);
    }

    public function getTraceId()
    {
        return $this->traceId;
    }

    public function getSpanId()
    {
        return $this->spanId;
    }

    public function isSampled()
    {
        return $this->isSampled;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function getBaggageItem($key)
    {
        return array_key_exists($key, $this->items) ? $this->items[$key] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function withBaggageItem($key, $value)
    {
        return new self($this->traceId, $this->spanId, $this->isSampled, array_merge($this->items, [$key => $value]));
    }

    private static function nextId()
    {
        return mt_rand(0, 99999);
    }
}
