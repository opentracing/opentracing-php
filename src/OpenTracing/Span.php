<?php

namespace OpenTracing;

use OpenTracing\Exceptions\SpanAlreadyFinished;

interface Span
{
    /**
     * @return string
     */
    public function getOperationName();

    /**
     * @return SpanContext
     */
    public function getContext();

    /**
     * @param float|int|\DateTimeInterface|null $finishTime if passing float or int
     * it should represent the timestamp (including as many decimal places as you need)
     * @param array $logRecords
     * @return mixed
     */
    public function finish($finishTime = null, array $logRecords = []);

    /**
     * @param string $newOperationName
     * @throws SpanAlreadyFinished if the span is already finished
     */
    public function overwriteOperationName($newOperationName);

    /**
     * Sets a tag to the Span.
     * As an implementor consider supporting a single Tag object or a $tag, $tagValue.
     *
     * @param array $tags
     * @throws SpanAlreadyFinished if the span is already finished
     */
    public function addTags(array $tags);

    /**
     * Adds a log record to the span
     * As an implementor, consider to use this one LogUtils\keyValueLogFieldsConverter
     *
     * @param array|LogRecord[] $fields
     * @param int|float|\DateTimeInterface $timestamp
     * @throws SpanAlreadyFinished if the span is already finished
     */
    public function addLog(array $fields = [], $timestamp = null);

    /**
     * @param string $key
     * @param string $value
     * @throws SpanAlreadyFinished if the span is already finished
     */
    public function addBaggageItem($key, $value);

    /**
     * @param string $key
     * @return string
     */
    public function getBaggageItem($key);
}
