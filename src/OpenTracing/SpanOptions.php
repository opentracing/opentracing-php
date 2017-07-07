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
     * @var array
     */
    private $tags = [];

    /**
     * @var int|float|\DateTime
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
                    $spanOptions->references = self::buildReferencesArray($value);
                    break;

                case 'tags':
                    foreach ($value as $tag => $tagValue) {
                        if ($tag !== (string) $tag) {
                            throw InvalidSpanOption::invalidTag($tag);
                        }

                        $spanOptions->tags[$tag] = $tagValue;
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
    public function getChildOf()
    {
        return $this->childOf;
    }

    /**
     * @return SpanReference[]
     */
    public function getReferences()
    {
        return $this->references;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return int|float|\DateTime if returning float or int it should represent
     * the timestamp (including as many decimal places as you need)
     */
    public function getStartTime()
    {
        return $this->startTime;
    }
}
