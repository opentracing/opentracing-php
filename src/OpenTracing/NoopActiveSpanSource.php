<?php

namespace OpenTracing;

final class NoopActiveSpanSource implements ActiveSpanSource
{
    public static function create()
    {
        return new self();
    }

    public function activate(Span $span)
    {
    }

    public function activeSpan()
    {
        return NoopSpan::create();
    }

    public function deactivate(Span $span)
    {
    }
}
