<?php

namespace OpenTracing\Exceptions;

use DomainException;

/**
 * Thrown when a reference has more than one parent in the SpanOptions
 */
final class InvalidReferencesSet extends DomainException
{
    /**
     * @param string $message
     * @return InvalidReferencesSet
     */
    public static function create(string $message): InvalidReferencesSet
    {
        return new self($message);
    }

    /**
     * @return InvalidReferencesSet
     */
    public static function forMoreThanOneParent(): InvalidReferencesSet
    {
        return new self('Span can not have more than one parent, either one as child_of or either one as follows_from');
    }
}
