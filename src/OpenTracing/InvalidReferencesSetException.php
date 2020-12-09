<?php

declare(strict_types=1);

namespace OpenTracing;

use DomainException;

/**
 * Thrown when a reference has more than one parent in the SpanOptions
 */
final class InvalidReferencesSetException extends DomainException
{
    /**
     * @param string $message
     * @return InvalidReferencesSetException
     */
    public static function create(string $message): InvalidReferencesSetException
    {
        return new self($message);
    }

    /**
     * @return InvalidReferencesSetException
     */
    public static function forMoreThanOneParent(): InvalidReferencesSetException
    {
        return new self('Span can not have more than one parent, either one as child_of or either one as follows_from');
    }
}
