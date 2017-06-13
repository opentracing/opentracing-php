<?php

namespace OpenTracing;

use Nanotime\Nanotime;

class NoopSpan implements Span
{
    public static function create()
    {
        return new self();
    }

    public function operationName()
    {
        return 'noop_span';
    }

    public function context()
    {
        return null;
    }

    public function finish(Nanotime $finishTime = null, $logRecords = [])
    {
    }

    public function overwriteOperationName($newOperationName)
    {
    }

    public function setTag(Tag $tag)
    {
    }

    public function logFields(LogField ...$logs)
    {
    }

    public function setBaggageItem($key, $value)
    {
    }

    public function baggageItem($key)
    {
        return null;
    }
}
