<?php

namespace OpenTracing\Propagators;

/**
 * Writer is the inject() carrier. With it, the caller can encode a SpanContext
 * for propagation.
 */
interface Writer
{
    /**
     * Set a key:value pair to the carrier. Multiple calls to set() for the
     * same key leads to undefined behavior.
     *
     * NOTE: The backing store for the Writer may contain data unrelated
     * to SpanContext. As such, inject() and extract() implementations that
     * call the TextMapWriter and TextMapReader interfaces must agree on a
     * prefix or other convention to distinguish their own key:value pairs.
     *
     * @param string $key
     * @param string $value
     */
    public function set($key, $value);
}
