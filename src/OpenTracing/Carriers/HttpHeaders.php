<?php

namespace OpenTracing\Carriers;

use ArrayIterator;
use OpenTracing\Propagation\Reader;
use OpenTracing\Propagation\Writer;
use Psr\Http\Message\RequestInterface;

final class HttpHeaders implements Reader, Writer
{
    private $items;

    private function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @param RequestInterface $request
     * @return HttpHeaders
     */
    public static function fromRequest(RequestInterface $request)
    {
        return new self(
            array_map(function ($values) {
                return implode(', ', $values);
            }, $request->getHeaders())
        );
    }

    public static function fromGlobals()
    {
        return new self(getallheaders());
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
