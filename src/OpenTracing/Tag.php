<?php

namespace OpenTracing;

use OpenTracing\Exceptions\InvalidTagArgument;

final class Tag
{
    private $key;
    private $value;

    private function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @param string $key
     * @param int|float|string|object $value only accepts objects with __toString method.
     * @return Tag
     */
    public static function create($key, $value)
    {
        if ($key !== (string) $key) {
            throw InvalidTagArgument::notStringKey($key);
        }

        if (is_object($value)) {
            if (method_exists($value, '__toString')) {
                return new self($key, $value);
            }

            throw InvalidTagArgument::notStringableValue($value);
        }

        if (!is_scalar($value)) {
            throw InvalidTagArgument::notScalarValue($value);
        }

        return new self($key, $value);
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return int|float|string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function is($key)
    {
        return $this->key == $key;
    }
}
