<?php

declare(strict_types=1);

namespace OpenTracing;

use DateTime;
use DateTimeInterface;
use OpenTracing\InvalidReferencesSetException;
use OpenTracing\InvalidSpanOptionException;

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
     * @var int|float|DateTimeInterface
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
     * @return StartSpanOptions
     * @throws InvalidReferencesSetException when there are inconsistencies about the references
     * @throws InvalidSpanOptionException when one of the options is invalid
     */
    public static function create(array $options): StartSpanOptions
    {
        $spanOptions = new self();

        foreach ($options as $key => $value) {
            switch ($key) {
                case 'child_of':
                    if (!empty($spanOptions->references)) {
                        throw InvalidSpanOptionException::forIncludingBothChildOfAndReferences();
                    }

                    $spanOptions->references[] = self::buildChildOf($value);
                    break;

                case 'references':
                    if (!empty($spanOptions->references)) {
                        throw InvalidSpanOptionException::forIncludingBothChildOfAndReferences();
                    }

                    if ($value instanceof Reference) {
                        $spanOptions->references = [$value];
                    } elseif (is_array($value)) {
                        $spanOptions->references = self::buildReferences($value);
                    } else {
                        throw InvalidSpanOptionException::forInvalidReferenceSet($value);
                    }

                    break;

                case 'tags':
                    if (!is_array($value)) {
                        throw InvalidSpanOptionException::forInvalidTags($value);
                    }

                    foreach ($value as $tag => $tagValue) {
                        if ($tag !== (string)$tag) {
                            throw InvalidSpanOptionException::forInvalidTag($tag);
                        }

                        $spanOptions->tags[$tag] = $tagValue;
                    }
                    break;

                case 'start_time':
                    if (is_scalar($value) && !is_numeric($value)) {
                        throw InvalidSpanOptionException::forInvalidStartTime();
                    }

                    $spanOptions->startTime = $value;
                    break;

                case 'finish_span_on_close':
                    if (!is_bool($value)) {
                        throw InvalidSpanOptionException::forFinishSpanOnClose($value);
                    }

                    $spanOptions->finishSpanOnClose = $value;
                    break;

                case 'ignore_active_span':
                    if (!is_bool($value)) {
                        throw InvalidSpanOptionException::forIgnoreActiveSpan($value);
                    }

                    $spanOptions->ignoreActiveSpan = $value;
                    break;

                default:
                    throw InvalidSpanOptionException::forUnknownOption($key);
            }
        }

        return $spanOptions;
    }

    /**
     * @param Span|SpanContext $parent
     * @return StartSpanOptions
     */
    public function withParent($parent): StartSpanOptions
    {
        $newSpanOptions = new StartSpanOptions();
        $newSpanOptions->references[] = self::buildChildOf($parent);
        $newSpanOptions->tags = $this->tags;
        $newSpanOptions->startTime = $this->startTime;
        $newSpanOptions->finishSpanOnClose = $this->finishSpanOnClose;
        $newSpanOptions->ignoreActiveSpan = $this->ignoreActiveSpan;

        return $newSpanOptions;
    }

    /**
     * @return Reference[]
     */
    public function getReferences(): array
    {
        return $this->references;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return int|float|DateTime|null if returning float or int it should represent
     * the timestamp (including as many decimal places as you need)
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @return bool
     */
    public function shouldFinishSpanOnClose(): bool
    {
        return $this->finishSpanOnClose;
    }

    /**
     * @return bool
     */
    public function shouldIgnoreActiveSpan(): bool
    {
        return $this->ignoreActiveSpan;
    }

    private static function buildChildOf($value): Reference
    {
        if ($value instanceof Span) {
            return Reference::createForSpan(Reference::CHILD_OF, $value);
        }

        if ($value instanceof SpanContext) {
            return new Reference(Reference::CHILD_OF, $value);
        }

        throw InvalidSpanOptionException::forInvalidChildOf($value);
    }

    private static function buildReferences(array $referencesArray): array
    {
        $references = [];

        foreach ($referencesArray as $reference) {
            if (!($reference instanceof Reference)) {
                throw InvalidSpanOptionException::forInvalidReference($reference);
            }

            $references[] = $reference;
        }

        return $references;
    }
}
