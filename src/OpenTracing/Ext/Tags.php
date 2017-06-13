<?php

namespace OpenTracing\Ext\Tags;

/**
 * SpanKind hints at relationship between spans, e.g. client/server
 */
const SPAN_KIND = 'span.kind';

/**
 * Component is a low-cardinality identifier of the module, library,
 * or package that is generating a span.
 */
const COMPONENT = 'component';

/**
 * PeerService records the service name of the peer
 */
const PEER_SERVICE = 'peer.service';

/**
 * PeerHostname records the host name of the peer
 */
const PEER_HOSTNAME = 'peer.hostname';

/**
 * HTTPUrl should be the URL of the request being handled in this segment
 * of the trace, in standard URI format. The protocol is optional.
 */
const HTTP_URL = 'http.url';

/**
 * HTTPMethod is the HTTP method of the request, and is case-insensitive.
 */
const HTTP_METHOD = 'http.method';

/**
 * HTTPStatusCode is the numeric HTTP status code (200, 404, etc) of the
 * HTTP response.
 */
const HTTP_STATUS_CODE = 'http.status_code';

/**
 * Error indicates that operation represented by the span resulted in an error.
 */
const ERROR = 'error';
