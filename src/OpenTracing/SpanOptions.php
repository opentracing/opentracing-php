<?php

namespace OpenTracing;

use OpenTracing\Exceptions\InvalidSpanOption;
use OpenTracing\SpanReference\ChildOf;

final class SpanOptions
{
    /**
     * @var ChildOf
     */
    private $childOf;

    /**
     * @var SpanReference[]
     */
    private $references = [];

    /**
     * @var Tag[]
     */
    private $tags = [];

    /**
     * @var mixed
     */
    private $startTime;

    public static function create(array $options)
    {
        $spanOptions = new self();
        
        foreach ($options as $key => $value) {
            switch ($key) {
                case 'child_of':
                    $spanOptions->childOf = self::buildChildOf($value);
                    break;

                case 'references':
                    $spanOptions->childOf = self::buildReferencesArray($value);
                    break;

                case 'tags':
                    foreach ($value as $tag => $tagValue) {
                        $spanOptions->tags[] = Tag::create($tag, $tagValue);
                    }
                    break;

                case 'start_time':
                    if (is_scalar($value) && !is_numeric($value)) {
                        throw InvalidSpanOption::invalidStartTime();
                    }

                    $spanOptions->startTime = $value;
                    break;

                default:
                    throw InvalidSpanOption::unknownOption($key);
                    break;
            }
        }

        if (count($spanOptions->references) > 0 && $spanOptions->childOf !== null) {
            throw InvalidSpanOption::includesBothChildOfAndReferences();
        }

        return $spanOptions;
    }

    private static function buildChildOf($value)
    {
        if ($value instanceof Span) {
            return ChildOf::fromContext($value->getContext());
        } elseif ($value instanceof SpanContext) {
            return ChildOf::fromContext($value);
        }

        throw InvalidSpanOption::invalidChildOf($value);
    }

    private static function buildReferencesArray(array $value)
    {
        $references = [];

        foreach ($value as $reference) {
            if (!($reference instanceof SpanReference)) {
                throw InvalidSpanOption::invalidReference($reference);
            }

            $references[] = $reference;
        }

        return $references;
    }

    /**
     * @return ChildOf
     */
    public function childOf()
    {
        return $this->childOf;
    }

    /**
     * @return Tag[]
     */
    public function tags()
    {
        return $this->tags;
    }

    /**
     * @return mixed
     */
    public function startTime()
    {
        return $this->startTime;
    }
}
