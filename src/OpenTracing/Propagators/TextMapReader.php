<?php

namespace OpenTracing\Propagators;

interface TextMapReader
{
    public function foreachKey(callable $callback);
}
