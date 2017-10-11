<?php

namespace OpenTracing\Propagation;

use IteratorAggregate;

/**
 * Reader is the extract() carrier for the TextMap builtin format. With it,
 * the caller can decode a propagated SpanContext as entries in a map of
 * unicode strings.
 */
interface Reader extends IteratorAggregate
{

}
