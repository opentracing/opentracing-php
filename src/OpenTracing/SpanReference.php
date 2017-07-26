<?php

namespace OpenTracing;

use OpenTracing\Exceptions\InvalidReferenceArgument;

final class SpanReference
{
    const CHILD_OF = 'child_of';
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
     * @param SpanContext $context
     */
    private function __construct($type, SpanContext $context)
    {
        $this->type = $type;
        $this->context = $context;
    }

    /**
     * @param SpanContext|Span $context
     * @param string $type
     * @throws InvalidReferenceArgument on empty type
     * @return SpanReference when context is invalid
     */
    public static function create($type, $context)
    {
        if (empty($type)) {
            throw InvalidReferenceArgument::emptyType();
        }

        return new self($type, self::extractContext($context));
    }

    /**
     * @param SpanContext|Span $context
     * @throws InvalidReferenceArgument when context is invalid
     * @return SpanReference
     */
    public static function createAsChildOf($context)
    {
        return new self(self::CHILD_OF, self::extractContext($context));
    }

    /**
     * @param SpanContext|Span $context
     * @throws InvalidReferenceArgument when context is invalid
     * @return SpanReference
     */
    public static function createAsFollowsFrom($context)
    {
        return new self(self::FOLLOWS_FROM, self::extractContext($context));
    }

    /**
     * @return SpanContext
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Checks whether a SpanReference is of one type.
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

        throw InvalidReferenceArgument::invalidContext($context);
    }
}
