<?php

namespace OpenTracing\LogUtils;

use Exception;
use InvalidArgumentException;
use OpenTracing\LogField;
use Throwable;

function interleavedKVToFieldsConverter(array $keyValueFields)
{
    $fields = [];

    foreach ($keyValueFields as $key => $value) {
        if (!is_string($key)) {
            throw new InvalidArgumentException(sprintf("Non-string key, got %s of type %s", $key, gettype($key)));
        }

        switch (true) {
            case $value === (bool) $value:
                $fields[] = LogField::asBool($key, $value);
                break;
            case $value === (float) $value || is_numeric($value):
                $fields[] = LogField::asFloat($key, $value);
                break;
            case $value === (int) $value:
                $fields[] = LogField::asInt($key, $value);
                break;
            case $value === (string) $value:
                $fields[] = LogField::asString($key, $value);
                break;
            case ($value instanceof Exception):
            case ($value instanceof Throwable):
                $fields[] = LogField::asError($key, $value);
                break;
            case is_object($value):
                $fields[] = LogField::asObject($key, $value);
                break;
        }
    }

    return $fields;
}
