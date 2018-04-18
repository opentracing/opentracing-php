<?php

namespace OpenTracing;

final class NoopScope implements Scope
{
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
        return new NoopSpan();
    }
}
