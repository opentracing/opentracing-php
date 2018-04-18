<?php

namespace OpenTracing;

use OpenTracing\Exceptions\InvalidReferencesSet;
use OpenTracing\Exceptions\InvalidSpanOption;

final class StartSpanOptions
{
    /**
     * @var Reference[]
     */
    private $references = [];

    /**
     * @var array
     */
    private $tags = [];

    /**
     * @var int|float|\DateTimeInterface
     */
    private $startTime;

    /**
     * Only used for spans that are actively managed by scope manager.
     *
     * @var bool
     */
    private $finishSpanOnClose = ScopeManager::DEFAULT_FINISH_SPAN_ON_CLOSE;

    /**
     * @var bool
     */
    private $ignoreActiveSpan = false;

    /**
     * @param array $options
     * @throws InvalidSpanOption when one of the options is invalid
     * @throws InvalidReferencesSet when there are inconsistencies about the references
     * @return StartSpanOptions
     */
    public function __construct(array $options)
    {
        foreach ($options as $key => $value) {
            switch ($key) {
                case 'child_of':
                    if (!empty($this->references)) {
                        throw InvalidSpanOption::forIncludingBothChildOfAndReferences();
                    }

                    $this->references[] = self::buildChildOf($value);
                    break;

                case 'references':
                    if (!empty($this->references)) {
                        throw InvalidSpanOption::forIncludingBothChildOfAndReferences();
                    }

                    if ($value instanceof Reference) {
                        $this->references = [$value];
                    } elseif (is_array($value)) {
                        $this->references = self::buildReferences($value);
                    } else {
                        throw InvalidSpanOption::forInvalidReferenceSet($value);
                    }

                    break;

                case 'tags':
                    if (!is_array($value)) {
                        throw InvalidSpanOption::forInvalidTags($value);
                    }

                    foreach ($value as $tag => $tagValue) {
                        if ($tag !== (string) $tag) {
                            throw InvalidSpanOption::forInvalidTag($tag);
                        }

                        $this->tags[$tag] = $tagValue;
                    }
                    break;

                case 'start_time':
                    if (is_scalar($value) && !is_numeric($value)) {
                        throw InvalidSpanOption::forInvalidStartTime();
                    }

                    $this->startTime = $value;
                    break;

                case 'finish_span_on_close':
                    if (!is_bool($value)) {
                        throw InvalidSpanOption::forFinishSpanOnClose($value);
                    }

                    $this->finishSpanOnClose = $value;
                    break;

                case 'ignore_active_span':
                    if (!is_bool($value)) {
                        throw InvalidSpanOption::forIgnoreActiveSpan($value);
                    }

                    $this->ignoreActiveSpan = $value;
                    break;

                default:
                    throw InvalidSpanOption::forUnknownOption($key);
                    break;
            }
        }
    }

    /**
     * @param Span|SpanContext $parent
     * @return StartSpanOptions
     */
    public function withParent($parent)
    {
        return new StartSpanOptions([
            'child_of' => $parent,
            'tags' => $this->tags,
            'start_time' => $this->startTime,
            'finish_span_on_close' => $this->finishSpanOnClose,
            'ignore_active_span' => $this->ignoreActiveSpan,
        ]);
    }

    /**
     * @return Reference[]
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
     * @return int|float|\DateTime|null if returning float or int it should represent
     * the timestamp (including as many decimal places as you need)
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @return bool
     */
    public function shouldFinishSpanOnClose()
    {
        return $this->finishSpanOnClose;
    }

    /**
     * @return bool
     */
    public function shouldIgnoreActiveSpan()
    {
        return $this->ignoreActiveSpan;
    }

    private static function buildChildOf($value)
    {
        if ($value instanceof Span) {
            return new Reference(Reference::CHILD_OF, $value->getContext());
        }

        if ($value instanceof SpanContext) {
            return new Reference(Reference::CHILD_OF, $value);
        }

        throw InvalidSpanOption::forInvalidChildOf($value);
    }

    private static function buildReferences(array $referencesArray)
    {
        $references = [];

        foreach ($referencesArray as $reference) {
            if (!($reference instanceof Reference)) {
                throw InvalidSpanOption::forInvalidReference($reference);
            }

            $references[] = $reference;
        }

        return $references;
    }
}
