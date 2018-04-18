<?php

namespace OpenTracing;

use OpenTracing\Exceptions\InvalidReferenceArgument;

final class Reference
{
    /**
     * A Span may be the ChildOf a parent Span. In a ChildOf reference,
     * the parent Span depends on the child Span in some capacity.
     */
    const CHILD_OF = 'child_of';

    /**
     * Some parent Spans do not depend in any way on the result of their
     * child Spans. In these cases, we say merely that the child Span
     * FollowsFrom the parent Span in a causal sense.
     */
    const FOLLOWS_FROM = 'follows_from';

    /**
     * @var string
     */
    private $type;

    /**
     * @var SpanContext
     */
    private $context;

    /**
     * @param string $type
     * @param SpanContext|Span $context
     */
    public function __construct($type, $context)
    {
        if (empty($type)) {
            throw InvalidReferenceArgument::forEmptyType();
        }

        $this->type = $type;
        $this->context = self::extractContext($context);
    }

    /**
     * @return SpanContext
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Checks whether a Reference is of one type.
     *
     * @param string $type the type for the reference
     * @return bool
     */
    public function isType($type)
    {
        return $this->type === $type;
    }

    private static function extractContext($context)
    {
        if ($context instanceof SpanContext) {
            return $context;
        }

        if ($context instanceof Span) {
            return $context->getContext();
        }

        throw InvalidReferenceArgument::forInvalidContext($context);
    }
}
