<?php

namespace OpenTracingTests\Unit;

use InvalidArgumentException;
use OpenTracing\LogField;
use PHPUnit_Framework_TestCase;

final class LogFieldTest extends PHPUnit_Framework_TestCase
{
    private $value;

    /** @var LogField */
    private $logField;

    /**
     * @dataProvider binaryProvider
     */
    public function testABoolSuccessOnCreationWithBinaryValues($binaryValue, $booleanValue)
    {
        $this->givenABinaryValue($binaryValue);
        $this->whenCreatingALogField();
        $this->thenTheBoolLogFieldIsCreatedAsExpected($booleanValue);
    }

    public function testABoolFailsOnCreationWithInvalidValues()
    {
        $this->givenAnInvalidValue();
        $this->thenAnInvalidArgumentExceptionIsThrown();
        $this->whenCreatingALogField();
    }

    public function binaryProvider()
    {
        return [
            [0, false],
            ['0', false],
            [1, true],
            ['1', true],
        ];
    }

    private function givenAnInvalidValue()
    {
        $this->value = 'invalid_value';
    }

    private function givenABinaryValue($binaryValue)
    {
        $this->value = $binaryValue;
    }

    private function whenCreatingALogField()
    {
        $this->logField = LogField::asBool('test_key', $this->value);
    }

    private function thenAnInvalidArgumentExceptionIsThrown()
    {
        $this->expectException(InvalidArgumentException::class);
    }

    private function thenTheBoolLogFieldIsCreatedAsExpected($booleanValue)
    {
        $this->assertTrue($this->logField->isBool());
        $this->assertEquals($this->logField->value(), $booleanValue);
    }
}
