<?php

declare(strict_types=1);

namespace OpenTracing;

use OpenTracing\InvalidReferenceArgumentException;

final class Reference
{
    /**
     * A Span may be the ChildOf a parent Span. In a ChildOf reference,
     * the parent Span depends on the child Span in some capacity.
     */
    public const CHILD_OF = 'child_of';

    /**
     * Some parent Spans do not depend in any way on the result of their
     * child Spans. In these cases, we say merely that the child Span
     * FollowsFrom the parent Span in a causal sense.
     */
    public const FOLLOWS_FROM = 'follows_from';

    /**
     * @var string
     */
    private $type;

    /**
     * @var SpanContext
     */
    private $spanContext;

    /**
     * @param string $type
     * @param SpanContext $spanContext
     */
    public function __construct(string $type, SpanContext $spanContext)
    {
        if (empty($type)) {
            throw InvalidReferenceArgumentException::forEmptyType();
        }

        $this->type = $type;
        $this->spanContext = $spanContext;
    }

    /**
     * @param string $type
     * @param Span $span
     * @return Reference when context is invalid
     * @throws InvalidReferenceArgumentException on empty type
     */
    public static function createForSpan(string $type, Span $span): Reference
    {
        return new self($type, $span->getContext());
    }

    /**
     * @return SpanContext
     */
    public function getSpanContext(): SpanContext
    {
        return $this->spanContext;
    }

    /**
     * Checks whether a Reference is of one type.
     *
     * @param string $type the type for the reference
     * @return bool
     */
    public function isType(string $type): bool
    {
        return $this->type === $type;
    }
}
