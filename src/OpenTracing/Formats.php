<?php

namespace OpenTracing\Formats;

/**
 * Used a (single) arbitrary binary blob representing a SpanContext
 */
const BINARY = 'binary';

/**
 * Used for an arbitrary string-to-string map with an unrestricted character set for both keys and values
 */
const TEXT_MAP = 'text_map';

/**
 * Used for a string-to-string map with keys and values that are suitable for use in HTTP headers (a la RFC 7230.
 * In practice, since there is such "diversity" in the way that HTTP headers are treated in the wild, it is strongly
 * recommended that Tracer implementations use a limited HTTP header key space and escape values conservatively.
 *
 * @see http://www.php-fig.org/psr/psr-7/#12-http-headers
 * @see http://php.net/manual/en/function.getallheaders.php
 */
const HTTP_HEADERS = 'http_headers';
