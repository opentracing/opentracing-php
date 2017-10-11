<?php

namespace OpenTracingTests\Unit\Carriers;

use OpenTracing\Carriers\TextMap;
use PHPUnit_Framework_TestCase;

/**
 * @covers TextMap
 */
final class TextMapTest extends PHPUnit_Framework_TestCase
{
    public function testCreateATextMapWithItemsSuccess()
    {
        $items = ['foo' => 'bar'];
        $textMap = TextMap::fromArray($items);

        foreach ($textMap as $key => $value) {
            $this->assertEquals('foo', $key);
            $this->assertEquals('bar', $value);
        }
    }

    public function testSettingAKeyValuePairSuccess()
    {
        $textMap = TextMap::fromArray([]);
        $textMap->set('foo', 'bar');

        foreach ($textMap as $key => $value) {
            $this->assertEquals('foo', $key);
            $this->assertEquals('bar', $value);
        }
    }
}
