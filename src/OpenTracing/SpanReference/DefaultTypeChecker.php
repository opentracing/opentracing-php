<?php

namespace OpenTracing\SpanReference;

/**
 * DefaultTypeChecker is a convenient way to save type method declaration for
 * SpanReferences.
 */
trait DefaultTypeChecker
{
    /**
     * @return bool
     */
    public function isTypeChildOf()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isTypeFollowsFrom()
    {
        return false;
    }
}
