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

    /**
     * @var string
     */
    private $key;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var int
     */
    private $type;

    private function __construct($key, $value, $type)
    {
        $this->key = $key;
        $this->value = $value;
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * Creates a LogField as string.
     *
     * @param $key
     * @param $value
     * @return LogField
     */
    public static function asString($key, $value)
    {
        self::validateKey($key);
        return new self($key, (string) $value, self::TYPE_STRING);
    }

    /**
     * @return bool
     */
    public function isString()
    {
        return $this->type === self::TYPE_STRING;
    }

    /**
     * @param string $key
     * @param int $value
     * @return LogField
     */
    public static function asInt($key, $value)
    {
        self::validateKey($key);

        if (!is_numeric($value)) {
            throw new InvalidArgumentException(sprintf('Value is not numeric, got %s', $value));
        }

        return new self($key, (int) $value, self::TYPE_INT);
    }

    /**
     * @return bool
     */
    public function isInt()
    {
        return $this->type === self::TYPE_INT;
    }

    /**
     * @param string $key
     * @param float $value
     * @return LogField
     */
    public static function asFloat($key, $value)
    {
        self::validateKey($key);

        if (!is_numeric($value)) {
            throw new InvalidArgumentException(sprintf('Value is not numeric, got %s', $value));
        }

        return new self($key, (float) $value, self::TYPE_FLOAT);
    }

    /**
     * @return bool
     */
    public function isFloat()
    {
        return $this->type === self::TYPE_FLOAT;
    }

    /**
     * @param string $key
     * @param boolean $value
     * @return LogField
     */
    public static function asBool($key, $value)
    {
        self::validateKey($key);

        if (in_array($value, [0, '0', false], true)) {
            $booleanValue = false;
        } elseif (in_array($value, [1, '1', true], true)) {
            $booleanValue = true;
        } else {
            throw new InvalidArgumentException(sprintf('Value is not a boolean nor a binary, got %s', $value));
        }

        return new self($key, $booleanValue, self::TYPE_BOOLEAN);
    }

    /**
     * @return bool
     */
    public function isBool()
    {
        return $this->type === self::TYPE_BOOLEAN;
    }

    /**
     * @param $key
     * @param $value
     * @return LogField
     */
    public static function asError($key, $value)
    {
        self::validateKey($key);

        switch (true) {
            case ($value instanceof Exception):
            case ($value instanceof Throwable):
                return new self($key, $value, self::TYPE_ERROR);
                break;
            default:
                throw new InvalidArgumentException(
                    sprintf('Value should be either exception or throwable. Got %s', gettype($value))
                );
                break;
        }
    }

    /**
     * @return bool
     */
    public function isError()
    {
        return $this->type === self::TYPE_ERROR;
    }

    /**
     * @param string $key
     * @param stdClass $value
     * @return LogField
     */
    public static function asObject($key, stdClass $value)
    {
        self::validateKey($key);
        return new self($key, $value, self::TYPE_OBJECT);
    }

    /**
     * @return bool
     */
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
