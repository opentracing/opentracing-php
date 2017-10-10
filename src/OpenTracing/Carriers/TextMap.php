<?php

namespace OpenTracing\Carriers;

use ArrayIterator;
use OpenTracing\Propagation\Reader;
use OpenTracing\Propagation\Writer;

final class TextMap implements Reader, Writer
{
    private $items = [];

    private function __construct(array $textMap)
    {
        foreach ($textMap as $key => $value) {
            $this->items[(string) $key] = (string) $value;
        }
    }

    /**
     * @param array|string[] $textMap
     * @return TextMap
     */
    public static function fromArray(array $textMap)
    {
        return new self($textMap);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        $this->items[(string) $key] = (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }
}
