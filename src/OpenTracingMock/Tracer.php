<?php

namespace OpenTracingMock;

use OpenTracing\Exceptions\UnsupportedFormat;
use OpenTracing\SpanOptions;
use OpenTracing\Tracer as OTTracer;
use OpenTracing\SpanContext as OTSpanContext;

final class Tracer implements OTTracer
{
    /**
     * @var array|Span[]
     */
    private $spans = [];

    /**
     * @var array|callable[]
     */
    private $injectors;

    /**
     * @var array|callable[]
     */
    private $extractors;

    private function __construct(array $injectors, array $extractors)
    {
        $this->injectors = $injectors;
        $this->extractors = $extractors;
    }

    public static function create(array $injectors = [], array $extractors = [])
    {
        return new self($injectors, $extractors);
    }

    /**
     * {@inheritdoc}
     */
    public function startSpan($operationName, $options = [])
    {
        if (!($options instanceof SpanOptions)) {
            $options = SpanOptions::create($options);
        }

        if ($options->getReferences()) {
            $spanContext = SpanContext::createAsRoot();
        } else {
            $spanContext = SpanContext::createAsChildOf($options->getReferences()[0]);
        }

        $span = Span::create(
            $operationName,
            $spanContext,
            $options->getStartTime()
        );

        $span->setTags($options->getTags());

        $this->spans[] = $span;
    }

    /**
     * {@inheritdoc}
     */
    public function inject(OTSpanContext $spanContext, $format, &$carrier)
    {
        if (!array_key_exists($format, $this->injectors)) {
            throw UnsupportedFormat::forFormat($format);
        }

        call_user_func($this->injectors[$format], $spanContext, $carrier);
    }

    /**
     * {@inheritdoc}
     */
    public function extract($format, $carrier)
    {
        if (!array_key_exists($format, $this->injectors)) {
            throw UnsupportedFormat::forFormat($format);
        }

        return call_user_func($this->extractors[$format], $carrier);
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        $this->spans = [];
    }
}
