<?php

namespace OpenTracing;

use Nanotime\Nanotime;

interface Span
{
    public function operationName();

    /** @return SpanContext */
    public function context();

    public function finish(Nanotime $finishTime = null);

    public function overwriteOperationName($newOperationName);

    public function setTag(Tag $tag);

    public function logFields(Log ...$logs);

    /** @return Span */
    public function setBaggageItem($key, $value);

    public function baggageItem($key);
}
