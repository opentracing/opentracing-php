<?php

namespace OpenTracing\Carriers;

use ArrayIterator;
use OpenTracing\Propagation\Reader;
use OpenTracing\Propagation\Writer;
use Psr\Http\Message\RequestInterface;

final class HttpHeaders implements Reader, Writer
{
    private $items = [];

    private function __construct(array $headers)
    {
        foreach ($headers as $key => $value) {
            $this->items[(string) $key] = (string) $value;
        }
    }

    /**
     * @param RequestInterface $request
     * @return HttpHeaders
     */
    public static function fromRequest(RequestInterface $request)
    {
        return new self(
            array_map(function ($values) {
                return $values[0];
            }, $request->getHeaders())
        );
    }

    /**
     * @param array|string[] $headers
     * @return HttpHeaders
     */
    public static function fromHeaders(array $headers)
    {
        return new self($headers);
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
