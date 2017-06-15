<?php

namespace OpenTracing\Carriers;

use ArrayIterator;
use OpenTracing\Propagators\Reader;
use OpenTracing\Propagators\Writer;

final class TextMap implements Reader, Writer
{
    private $items = [];

    private function __construct(array $textMap)
    {
        foreach ($textMap as $key => $value) {
            $this->items[(string) $key] = (string) $value;
        }
    }

    public static function create(array $textMap = [])
    {
        return new self($textMap);
    }

    public function set($key, $value)
    {
        $this->items[(string) $key] = (string) $value;
    }

    /** @deprecated use its implementation for Iterator instead */
    public function foreachKey(callable $callback)
    {
        array_walk($this->items, function ($value, $key) use ($callback) {
            $callback($key, $value);
        });
    }

    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }
}
