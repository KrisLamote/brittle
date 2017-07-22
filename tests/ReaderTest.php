<?php

namespace KrisLamote\Brittle\Tests;

use KrisLamote\Brittle\Exception\RecordLengthException;
use KrisLamote\Brittle\FixedField;
use KrisLamote\Brittle\OffsetField;
use KrisLamote\Brittle\Reader;
use \Mockery as Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Class ReaderTest
 * @package KrisLamote\Brittle\Tests
 */
class ReaderTest extends TestCase
{
    /**
     * @ test Can be instantiated from a string
     */
    public function canBeInstantiatedFromAString()
    {
        $reader = Reader::fromString($this->fileString());

        $this->assertInstanceOf(Reader::class, $reader);
    }

    /**
     * @test Verify file counters including boundary cases
     */
    public function countsFileRecords()
    {
        foreach ([0, 1, 2, 13] as $count) {
            $reader = Reader::fromString($this->fileString($count));
            $this->assertEquals($count, $reader->count());
        }
    }

    /**
     * @test Verify file record length
     */
    public function checksRecordLengths()
    {
        $reader = Reader::fromString($this->fileString(2));
        $this->assertEquals(50, $reader->recordLength());

        $reader = Reader::fromString($this->fileString(2, 42));
        $this->assertEquals(42, $reader->recordLength());
    }

    /**
     * @test fails when the file contains variable length records
     */
    public function failsWithVariableRecordLengths()
    {
        $this->expectException(RecordLengthException::class);

        $fileString = implode(
            PHP_EOL,
            [$this->recordString(21), $this->recordString(20)]
        );

        Reader::fromString($fileString);
    }

    /**
     * @todo Probably fits better as an integration test
     * @test
     */
    public function parsesOffsetField()
    {
        $field = Mockery::mock(OffsetField::class);
        $field->shouldReceive('getLabel')->andReturn('foo')
              ->shouldReceive('awkSubstr')->andReturn('substr($0, 8, 2)');

        $reader = Reader::fromString($this->fileString(1))
                          ->withField($field)
                          ->parse();

        $row = $reader->first();
        $this->assertTrue(property_exists($row, 'foo'), "'foo' property is missing");
        $this->assertEquals('89', $row->foo);
    }

    /**
     * @todo Probably fits better as an integration test
     * @test
     * @group temp
     */
    public function parsesFixedField()
    {
        $field = Mockery::mock(FixedField::class);
        $field->shouldReceive('getLabel')->andReturn('foo')
            ->shouldReceive('awkSubstr')->andReturn('"bar"');

        $reader = Reader::fromString($this->fileString(1))
            ->withField($field)
            ->parse();

        $row = $reader->first();
        $this->assertTrue(property_exists($row, 'foo'), "'foo' property is missing");
        $this->assertEquals('bar', $row->foo);
    }

    /**
     * @test
     */
    public function parsesMultipleFields()
    {
        $fields = [
            new OffsetField('foo', 8, 2),
            new OffsetField('bar', 3, 5)
        ];
        $reader = Reader::fromString($this->fileString(2))
            ->withFields($fields)
            ->parse();

        $row = $reader->first();
        $this->assertTrue(property_exists($row, 'foo'), "'foo' property is missing");
        $this->assertEquals('89', $row->foo);

        $row = $reader->next();
        $this->assertTrue(property_exists($row, 'bar'), "'bar' property is missing");
        $this->assertEquals('34567', $row->bar);
    }

    /**
     * @test
     */
    public function savesToCsvFile()
    {
        $filePath = __DIR__ . '/' . uniqid();

        Reader::fromString($this->fileString(2))
            ->withFields([new OffsetField('foo', 8, 2), new OffsetField('bar', 3, 5)])
            ->parse()
            ->toCsv($filePath);

        $this->assertTrue(file_exists($filePath));

        $lineCount = 0;
        $csv = fopen($filePath, 'r');
        while(!feof($csv)){
            $row = fgets($csv);
            if (!empty(preg_replace( "/\r|\n/", '', $row))) {
                $lineCount++;
            }
        }

        fclose($csv);
        unlink($filePath);

        $this->assertEquals(3, $lineCount);
    }

    /**
     * @param int $lineCount
     * @param int $length
     * @return string
     */
    private function fileString($lineCount = 1, $length = 50)
    {
        if ($lineCount < 1) {
            return '';
        }

        return implode(
            PHP_EOL,
            array_fill(0, $lineCount, $this->recordString($length))
        );
    }

    /**
     * @param int $length
     * @return string
     */
    private function recordString($length)
    {
        $sequence = '1234567890';

        return str_repeat($sequence, floor($length / 10)) . substr($sequence, 0, $length % 10);
    }

}