<?php

namespace OpenTracing;

use Nanotime\Nanotime;

interface Span
{
    public function operationName();

    /** @return SpanContext */
    public function context();

    public function finish(Nanotime $finishTime = null, $logRecords = []);

    public function overwriteOperationName($newOperationName);

    public function setTag(Tag $tag);

    public function logFields(LogField ...$logs);

    /**
     * log is a concise, readable way to record key:value logging data about a Span.
     * As an implementor, you would like to use this one LogUtils\interleavedKVToFieldsConverter
     */
    public function log(array $logs);

    /** @return Span */
    public function setBaggageItem($key, $value);

    public function baggageItem($key);
}
