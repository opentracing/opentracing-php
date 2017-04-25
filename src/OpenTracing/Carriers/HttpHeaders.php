<?php

namespace OpenTracing\Carriers;

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

    public static function fromRequest(RequestInterface $request)
    {
        return new self(
            array_map(function($values) {
                return $values[0];
            }, $request->getHeaders())
        );
    }

    public static function withHeaders(array $headers)
    {
        return new self($headers);
    }

    public function set($key, $value)
    {
        $this->items[(string) $key] = (string) $value;
    }

    public function foreachKey(callable $callback)
    {
        array_walk($this->items, function($value, $key) use ($callback) {
            $callback($key, $value);
        });
    }
}
