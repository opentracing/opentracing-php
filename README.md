# OpenTracing API for PHP

[![Build Status](https://travis-ci.org/opentracing/opentracing-php.svg?branch=master)](https://travis-ci.org/opentracing/opentracing-php)
[![OpenTracing Badge](https://img.shields.io/badge/OpenTracing-enabled-blue.svg)](http://opentracing.io)
[![Total Downloads](https://poser.pugx.org/opentracing/opentracing/downloads)](https://packagist.org/packages/opentracing/opentracing)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.6-8892BF.svg)](https://php.net/)
[![License](https://img.shields.io/packagist/l/opentracing/opentracing.svg)](https://github.com/opentracing/opentracing-php/blob/master/LICENSE)
[![Join the chat at https://gitter.im/opentracing/opentracing-php](https://badges.gitter.im/opentracing/opentracing-php.svg)](https://gitter.im/opentracing/opentracing-php?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

PHP library for the OpenTracing's API.

## Required Reading

In order to understand the library, one must first be familiar with the
[OpenTracing project](http://opentracing.io) and
[specification](http://opentracing.io/documentation/pages/spec.html) more specifically.

## Installation

OpenTracing-PHP can be installed via Composer:

```bash
composer require opentracing/opentracing
```

## Usage

When consuming this library one really only need to worry about a couple of key
abstractions: the `Tracer::startActiveSpan` and `Tracer::startSpan` method,
the `Span` interface, and binding a `Tracer` at bootstrap time. Here are code snippets
demonstrating some important use cases:

### Singleton initialization

The simplest starting point is to set the global tracer. As early as possible, do:

```php
use OpenTracing\GlobalTracer;

GlobalTracer::set(new MyTracerImplementation());
```

### Creating a Span given an existing Request

To start a new `Span`, you can use the `startActiveSpan` method.

```php
use OpenTracing\Formats;
use OpenTracing\GlobalTracer;

...

$spanContext = GlobalTracer::get()->extract(
    Formats\HTTP_HEADERS,
    getallheaders()
);

function doSomething() {
    ...
    
    $span = GlobalTracer::get()->startActiveSpan('my_span', ['child_of' => $spanContext]);
    
    ...
    
    $span->log([
        'event' => 'soft error',
        'type' => 'cache timeout',
        'waiter.millis' => 1500,
    ])
    
    $span->finish();
}
```

### Starting an empty trace by creating a "root span"

It's always possible to create a "root" `Span` with no parent or other causal reference.

```php
$span = $tracer->startActiveSpan('my_first_span');
...
$span->finish();
```

#### Creating a child span assigning parent manually

```php
$parent = GlobalTracer::get()->startSpan('parent');

$child = GlobalTracer::get()->startSpan('child', [
    'child_of' => $parent
]);

...

$child->finish();

...

$parent->finish();
```

#### Creating a child span using automatic active span management

Every new span will take the active span as parent and it will take its spot.

```php
$parent = GlobalTracer::get()->startActiveSpan('parent');        

...

/*
 * Since the parent span has been created by using startActiveSpan we don't need
 * to pass a reference for this child span
 */
$child = GlobalTracer::get()->startActiveSpan('my_second_span');

... 

$child->finish();

...

$parent->finish();
```

### Serializing to the wire

```php
use GuzzleHttp\Client;
use OpenTracing\Formats;

...

$tracer = GlobalTracer::get(); 

$spanContext = $tracer->extract(
    Formats\HTTP_HEADERS,
    getallheaders()
);

try {
    $span = $tracer->startSpan('my_span', ['child_of' => $spanContext]);

    $client = new Client;
    
    $headers = [];
    
    $tracer->inject(
        $span->getContext(),
        Formats\HTTP_HEADERS,
        $headers
    );
    
    $request = new \GuzzleHttp\Psr7\Request('GET', 'http://myservice', $headers);
    $client->send($request);
    ...
    
} catch (\Exception $e) {
    ...
}
...    
```

### Deserializing from the wire

When using http header for context propagation you can use either the `Request` or the `$_SERVER`
variable:

```php
use OpenTracing\GlobalTracer;
use OpenTracing\Formats;

$tracer = GlobalTracer::get();
$spanContext = $tracer->extract(Formats\HTTP_HEADERS, getallheaders());
$tracer->startSpan('my_span', [
    'child_of' => $spanContext,
]); 
```

### Flushing Spans

PHP as a request scoped language has no simple means to pass the collected spans
data to a background process without blocking the main request thread/process.
The OpenTracing API makes no assumptions about this, but for PHP that might
cause problems for Tracer implementations. This is why the PHP API contains a
`flush` method that allows to trigger a span sending out of process.

```php
use OpenTracing\GlobalTracer;

$application->run();

register_shutdown_function(function() use ($tracer) {
    /* Flush the tracer to the backend */
    $tracer = GlobalTracer::get();
    $tracer->flush();
});
```

This is optional, tracers can decide to immediately send finished spans to a
backend. The flush call can be implemented as a NO-OP for these tracers.

### Using Span Options

Passing options to the pass can be done using either an array or the
SpanOptions wrapper object. The following keys are valid:

- `start_time` is a float, int or `\DateTime` representing a timestamp with arbitrary precision.
- `child_of` is an object of type `OpenTracing\SpanContext` or `OpenTracing\Span`.
- `references` is an array of `OpenTracing\Reference`. 
- `tags` is an array with string keys and scalar values that represent OpenTracing tags.

```php
$span = $tracer->startActiveSpan('my_span', [
    'child_of' => $spanContext,
    'tags' => ['foo' => 'bar'],
    'start_time' => time(),
]);
```

### Propagation Formats

The propagation formats should be implemented consistently across all tracers.
If you want to implement your own format, then don't reuse the existing constants.
Tracers will throw an exception if the requested format is not handled by them.

- `Tracer::FORMAT_TEXT_MAP` should represents the span context as a key value map. There is no
  assumption about the semantics where the context is coming from and sent to.

- `Tracer::FORMAT_HTTP_HEADERS` should represent the span context as HTTP header lines
  in an array list. For two context details "Span-Id" and "Trace-Id", the
  result would be `['Span-Id: abc123', 'Trace-Id: def456']`. This definition can be
  passed directly to `curl` and `file_get_contents`.

- `Tracer::FORMAT_BINARY` makes no assumptions about the data format other than it is
  proprietary and each Tracer can handle it as it wants.

## Coding Style

OpenTracing PHP follows the [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
coding standard and the [PSR-4](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md) autoloading standard.
