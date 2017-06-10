<?php

namespace OpenTracingTests\Unit;

use OpenTracing\Exceptions\InvalidTagValue;
use OpenTracing\Tag;
use PHPUnit_Framework_TestCase;

final class TagTest extends PHPUnit_Framework_TestCase
{
    const TEST_VALUE = 'test_value';
    const TEST_KEY = 'test_key';

    private $value;
    private $tag;

    public function testTagCreationFailsOnNonScalar()
    {
        $this->givenANonScalarValue();
        $this->thenAnInvalidTagValueIsThrown();
        $this->whenCreatingATag();
    }

    public function testTagCreationSuccessWithScalarValue()
    {
        $this->givenAScalarValue();
        $this->whenCreatingATag();
        $this->thenTheTagIsCreateWithTheRightValues();
    }

    public function testTagCreationSuccessWithStringableObjectValue()
    {
        $this->givenAStringableObjectValue();
        $this->whenCreatingATag();
        $this->thenTheTagIsCreateWithTheRightValuesAsString();
    }

    private function givenANonScalarValue()
    {
        $this->value = [];
    }

    private function givenAStringableObjectValue()
    {
        $this->value = new TagTestStringable(self::TEST_VALUE);
    }

    private function givenAScalarValue()
    {
        $this->value = self::TEST_VALUE;
    }

    private function thenAnInvalidTagValueIsThrown()
    {
        $this->expectException(InvalidTagValue::class);
    }

    private function whenCreatingATag()
    {
        $this->tag = Tag::create(self::TEST_KEY, $this->value);
    }

    private function thenTheTagIsCreateWithTheRightValues()
    {
        $this->assertEquals(self::TEST_VALUE, $this->value);
    }

    private function thenTheTagIsCreateWithTheRightValuesAsString()
    {
        $this->assertEquals(self::TEST_VALUE, (string) $this->value);
    }
}
