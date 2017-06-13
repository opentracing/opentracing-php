<?php

namespace OpenTracing\Propagators;

interface TextMapWriter
{
    /**
     * @param string $key
     * @param string $value
     */
    public function set($key, $value);
}
