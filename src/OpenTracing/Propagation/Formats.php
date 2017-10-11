<?php

namespace OpenTracing\Propagation\Formats;

use OpenTracing\Carriers\HttpHeaders;
use OpenTracing\Carriers\TextMap;

const BINARY = 'binary';

/**
 * @see HttpHeaders
 */
const TEXT_MAP = 'text_map';

/**
 * @see TextMap
 */
const HTTP_HEADERS = 'http_headers';
