<?php

namespace OpenTracingTests\Unit;

use InvalidArgumentException;
use OpenTracing\LogField;
use OpenTracing\LogRecord;
use PHPUnit_Framework_TestCase;

final class LogRecordTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var LogField[]
     */
    private $fields;

    /**
     * @var LogRecord
     */
    private $logRecord;

    public function testALogRecordCannotBeCreatedWithoutLogFields()
    {
        $this->givenNoLogFields();
        $this->thenAnInvalidArgumentExceptionIsThrown();
        $this->whenCreatingALogRecord();
    }

    public function testALogRecordIsSuccessfullyCreateWithLogFields()
    {
        $this->givenLogFields();
        $this->whenCreatingALogRecord();
        $this->thenTheLogRecordIsCreatedSuccessfully();
    }

    private function givenNoLogFields()
    {
        $this->fields = [];
    }

    private function givenLogFields()
    {
        $this->fields = [
            LogField::asString('test_string', 'test_value'),
            LogField::asInt('test_int', 2),
        ];
    }

    private function thenAnInvalidArgumentExceptionIsThrown()
    {
        $this->expectException(InvalidArgumentException::class);
    }

    private function whenCreatingALogRecord()
    {
        $this->logRecord = call_user_func(LogRecord::class . '::create', $this->fields);
    }

    private function thenTheLogRecordIsCreatedSuccessfully()
    {
        $this->assertCount(2, $this->logRecord->getFields());
    }
}
