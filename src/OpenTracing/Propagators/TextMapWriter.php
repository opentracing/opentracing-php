<?php

namespace OpenTracing\Propagators;

interface TextMapWriter
{
    public function set($key, $value);
}
