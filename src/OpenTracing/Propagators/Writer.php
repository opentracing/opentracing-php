<?php

namespace OpenTracing\Propagators;

interface Writer
{
    /**
     * @param string $key
     * @param string $value
     */
    public function set($key, $value);
}
