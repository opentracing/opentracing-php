<?php

namespace OpenTracing\Carriers;

use ArrayIterator;
use OpenTracing\Propagators\TextMapReader;
use OpenTracing\Propagators\TextMapWriter;
use Psr\Http\Message\RequestInterface;

final class HttpHeaders implements TextMapReader, TextMapWriter
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
     * @param array $headers
     * @return HttpHeaders
     */
    public static function withHeaders(array $headers)
    {
        return new self($headers);
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function set($key, $value)
    {
        $this->items[(string) $key] = (string) $value;
    }

    /**
     * @deprecated use its implementation for Iterator instead
     * @param callable $callback
     */
    public function foreachKey(callable $callback)
    {
        array_walk($this->items, function ($value, $key) use ($callback) {
            $callback($key, $value);
        });
    }

    /**
     * Allows you to iterate over HttpHeaders with foreach
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }
}
