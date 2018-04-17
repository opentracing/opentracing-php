<?php

namespace OpenTracing\Tags;

/**
 * SpanKind hints at relationship between spans, e.g. client/server
 */
const SPAN_KIND = 'span.kind';

/**
 * Marks a span representing the client-side of an RPC or other remote call
 */
const SPAN_KIND_RPC_CLIENT = 'client';

/**
 * Marks a span representing the server-side of an RPC or other remote call
 */
const SPAN_KIND_RPC_SERVER = 'server';

/**
 * Marks a span as representing the producer within a messaging context
 */
const SPAN_KIND_MESSAGE_BUS_PRODUCER = 'producer';

/**
 * Marks a span as representing the consumer within a messaging context
 */
const SPAN_KIND_MESSAGE_BUS_CONSUMER = 'consumer';

/**
 * Component is a low-cardinality identifier of the module, library,
 * or package that is generating a span.
 */
const COMPONENT = 'component';

/**
 * SAMPLING_PRIORITY (uint16) determines the priority of sampling this Span.
 */
const SAMPLING_PRIORITY = 'sampling.priority';

/**
 * PeerService records the service name of the peer
 */
const PEER_SERVICE = 'peer.service';

/**
 * PeerHostname records the host name of the peer
 */
const PEER_HOSTNAME = 'peer.hostname';

/**
 * PEER_ADDRESS (string) suitable for use in a networking client library.
 * This may be a "ip:port", a bare "hostname", a FQDN, or even a # JDBC
 * substring like "mysql://prod-db:3306"
 */
const PEER_ADDRESS = 'peer.address';

/**
 * PEER_HOST_IPV4 (uint32) records IP v4 host address of the peer
 */
const PEER_HOST_IPV4 = 'peer.ipv4';

/**
 * PEER_HOST_IPV6 (string) records IP v6 host address of the peer
 */
const PEER_HOST_IPV6 = 'peer.ipv6';

/** PEER_PORT (uint16) records port number of the peer */
const PEER_PORT = 'peer.port';

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
 * DATABASE_INSTANCE (string) The database instance name.
 */
const DATABASE_INSTANCE = 'db.instance';

/**
 * DATABASE_STATEMENT (string) A database statement for the given database
 * type. E.g., for db.type="SQL", "SELECT * FROM user_table"; # for
 * db.type="redis", "SET mykey 'WuValue'". */
const DATABASE_STATEMENT = 'db.statement';

/**
 * DATABASE_TYPE (string) For any SQL database, "sql". For others, the lower-case
 * database category, e.g. "cassandra", "hbase", or "redis".
 */
const DATABASE_TYPE = 'db.type';

/**
 * DATABASE_USER (string) Username for accessing database. E.g., "readonly_user" or
 * "reporting_user"
 */
const DATABASE_USER = 'db.user';

/**
 * MESSAGE_BUS_DESTINATION (string) An address at which messages can be #
 * exchanged. E.g. A Kafka record has an associated "topic name" that can #
 * be extracted by the instrumented producer or consumer and stored # using
 * this tag.
 */
const MESSAGE_BUS_DESTINATION = 'message_bus.destination';

/**
 * Error indicates that operation represented by the span resulted in an error.
 */
const ERROR = 'error';
