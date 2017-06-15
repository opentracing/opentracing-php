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
    public function operationName()
    {
        return 'noop_span';
    }

    /**
     * {@inheritdoc}
     */
    public function context()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function finish($finishTime = null, $logRecords = [])
    {
    }

    public function overwriteOperationName($newOperationName)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function addTags(array $tags)
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
    public function baggageItem($key)
    {
        return null;
    }
}
