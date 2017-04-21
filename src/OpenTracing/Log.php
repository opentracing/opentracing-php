<?php

namespace OpenTracing;

use Exception;
use InvalidArgumentException;

final class Log
{
    private $key;
    private $value;

    private function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public static function asString($key, $value)
    {
        self::validateKey($key);
        return new self($key, (string) $value);
    }

    public static function asInt($key, $value)
    {
        self::validateKey($key);
        return new self($key, (int) $value);
    }

    public static function asBool($key, $value)
    {
        self::validateKey($key);
        return new self($key, (bool) $value);
    }

    public static function asException($key, Exception $value)
    {
        self::validateKey($key);
        return new self($key, $value);
    }

    private static function validateKey($key)
    {
        if (!is_string($key) || empty($key)) {
            throw new InvalidArgumentException('key must be a non empty string.');
        }
    }
}
