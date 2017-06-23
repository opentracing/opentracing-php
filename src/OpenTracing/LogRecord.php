<?php

namespace OpenTracing;

use InvalidArgumentException;

/**
 * LogRecord is data associated with a single Span log. Every LogRecord
 * instance must specify at least one Field.
 */
final class LogRecord
{
    private $timestamp;
    private $fields = [];

    private function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public static function create(array $fields, $timestamp = null)
    {
        if (count($fields) === 0) {
            throw new InvalidArgumentException('At least one field should be included.');
        }

        return new self($fields, $timestamp);
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function getFields()
    {
        return $this->fields;
    }
}
