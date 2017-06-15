<?php

namespace OpenTracing;

interface Span
{
    /**
     * @return string
     */
    public function operationName();

    /**
     * @return SpanContext
     */
    public function context();

    /**
     * @param float|int|\DateTimeInterface|null $finishTime if passing float or int
     * it should represent the timestamp (including as many decimal places as you need)
     * @param array $logRecords
     * @return mixed
     */
    public function finish($finishTime = null, array $logRecords = []);

    /**
     * @param string $newOperationName
     */
    public function overwriteOperationName($newOperationName);

    /**
     * Sets a tag to the Span.
     * As an implementor consider supporting a single Tag object or a $tag, $tagValue.
     *
     * @param array $tags
     */
    public function addTags(array $tags);

    /**
     * Adds a log record to the span
     * As an implementor, consider to use this one LogUtils\keyValueLogFieldsConverter
     *
     * @param array|LogRecord[] $fields
     * @param int|float|\DateTimeInterface $timestamp
     */
    public function log(array $fields = [], $timestamp = null);

    /**
     * @param string $key
     * @param string $value
     * @return Span
     */
    public function addBaggageItem($key, $value);

    /**
     * @param string $key
     * @return string
     */
    public function baggageItem($key);
}
