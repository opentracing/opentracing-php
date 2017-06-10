<?php

namespace OpenTracing;

use Exception;
use InvalidArgumentException;
use stdClass;
use Throwable;

final class LogField
{
    const TYPE_STRING = 1;
    const TYPE_BOOLEAN = 2;
    const TYPE_INT = 3;
    const TYPE_FLOAT = 4;
    const TYPE_ERROR = 5;
    const TYPE_OBJECT = 6;

    private $key;
    private $value;
    private $type;

    private function __construct($key, $value, $type)
    {
        $this->key = $key;
        $this->value = $value;
        $this->type = $type;
    }

    public static function asString($key, $value)
    {
        self::validateKey($key);
        return new self($key, (string) $value, self::TYPE_STRING);
    }

    public function isString()
    {
        return $this->type === self::TYPE_STRING;
    }

    public static function asInt($key, $value)
    {
        self::validateKey($key);
        return new self($key, (int) $value, self::TYPE_INT);
    }

    public function isInt()
    {
        return $this->type === self::TYPE_INT;
    }

    public static function asFloat($key, $value)
    {
        self::validateKey($key);
        return new self($key, (float) $value, self::TYPE_FLOAT);
    }

    public function isFloat()
    {
        return $this->type === self::TYPE_FLOAT;
    }

    public static function asBool($key, $value)
    {
        self::validateKey($key);
        return new self($key, (bool) $value, self::TYPE_BOOLEAN);
    }

    public function isBool()
    {
        return $this->type === self::TYPE_BOOLEAN;
    }

    public static function asError($key, $value)
    {
        self::validateKey($key);

        if ($value instanceof Exception) {
            return new self($key, $value, self::TYPE_ERROR);
        }

        if ($value instanceof Throwable) {
            return new self($key, $value, self::TYPE_ERROR);
        }

        throw new InvalidArgumentException(
            sprintf("Value should be either exception or throwable. Got %s", gettype($value))
        );
    }

    public function isError()
    {
        return $this->type === self::TYPE_ERROR;
    }

    public static function asObject($key, stdClass $value)
    {
        self::validateKey($key);
        return new self($key, $value, self::TYPE_OBJECT);
    }

    public function isObject()
    {
        return $this->type === self::TYPE_OBJECT;
    }

    private static function validateKey($key)
    {
        if (!is_string($key) || empty($key)) {
            throw new InvalidArgumentException('key must be a non empty string.');
        }
    }
}
