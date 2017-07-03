<?php

namespace OpenTracing\Carriers;

use ArrayIterator;
use OpenTracing\Propagators\Reader;
use OpenTracing\Propagators\Writer;
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
     * @param array $headers
     * @return HttpHeaders
     */
    public static function fromHeaders(array $headers)
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
     * Allows you to iterate over HttpHeaders with foreach
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }
}
