<?php

namespace OpenTracing;

interface SpanReference
{
    /**
     * @return bool
     */
    public function isTypeChildOf();

    /**
     * @return bool
     */
    public function isTypeFollowsFrom();

    /**
     * @return SpanContext
     */
    public function referencedContext();
}
