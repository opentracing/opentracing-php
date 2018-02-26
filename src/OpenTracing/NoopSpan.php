<?php

namespace OpenTracing;

final class NoopSpan implements Span
{
    public static function create()
    {
        return new self();
    }

    /**
     * {@inheritdoc}
     */
    public function getOperationName()
    {
        return 'noop_span';
    }

    /**
     * {@inheritdoc}
     */
    public function getContext()
    {
        return NoopSpanContext::create();
    }

    /**
     * {@inheritdoc}
     */
    public function finish($finishTime = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function overwriteOperationName($newOperationName)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setTag($key, $value)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function log(array $fields = [], $timestamp = null)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addBaggageItem($key, $value)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBaggageItem($key)
    {
        return null;
    }
}
