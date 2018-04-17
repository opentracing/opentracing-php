<?php

namespace OpenTracing;

final class NoopScope implements Scope
{
    public static function create()
    {
        return new self();
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getSpan()
    {
        return NoopSpan::create();
    }
}
