<?php

declare(strict_types=1);

namespace OpenTracing;

use DateTimeInterface;

interface Span
{
    /**
     * @return string
     */
    public function getOperationName(): string;

    /**
     * Yields the SpanContext for this Span. Note that the return value of
     * Span::getContext() is still valid after a call to Span::finish(), as is
     * a call to Span::getContext() after a call to Span::finish().
     *
     * @return SpanContext
     */
    public function getContext(): SpanContext;

    /**
     * Sets the end timestamp and finalizes Span state.
     *
     * With the exception of calls to getContext() (which are always allowed),
     * finish() must be the last call made to any span instance, and to do
     * otherwise leads to undefined behavior but not returning an exception.
     *
     * As an implementor, make sure you call {@see Tracer::deactivate()}
     * otherwise new spans might try to be child of this one.
     *
     * If the span is already finished, a warning should be logged.
     *
     * @param float|int|DateTimeInterface|null $finishTime if passing float or int
     * it should represent the timestamp (including as many decimal places as you need)
     * @return void
     */
    public function finish($finishTime = null): void;

    /**
     * If the span is already finished, a warning should be logged.
     *
     * @param string $newOperationName
     * @return void
     */
    public function overwriteOperationName(string $newOperationName): void;

    /**
     * Adds a tag to the span.
     *
     * If there is a pre-existing tag set for key, it is overwritten.
     *
     * As an implementor, consider using "standard tags" listed in {@see \OpenTracing\Tags}
     *
     * If the span is already finished, a warning should be logged.
     *
     * @param string $key
     * @param string|bool|int|float $value
     * @return void
     */
    public function setTag(string $key, $value): void;

    /**
     * Adds a log record to the span in key => value format, key must be a string and tag must be either
     * a string, a boolean value, or a numeric type.
     *
     * If the span is already finished, a warning should be logged.
     *
     * @param array $fields
     * @param int|float|DateTimeInterface $timestamp
     * @return void
     */
    public function log(array $fields = [], $timestamp = null): void;

    /**
     * Adds a baggage item to the SpanContext which is immutable so it is required to use
     * SpanContext::withBaggageItem to get a new one.
     *
     * If the span is already finished, a warning should be logged.
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    public function addBaggageItem(string $key, string $value): void;

    /**
     * @param string $key
     * @return string|null returns null when there is not a item under the provided key
     */
    public function getBaggageItem(string $key): ?string;
}
