<?php

namespace OpenTracing;

final class NoopSpan implements Span
{
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
        return new NoopSpanContext();
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
    }

    /**
     * {@inheritdoc}
     */
    public function setTag($key, $value)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function log(array $fields = [], $timestamp = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function addBaggageItem($key, $value)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getBaggageItem($key)
    {
        return null;
    }
}
