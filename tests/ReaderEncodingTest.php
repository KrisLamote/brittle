<?php

namespace KrisLamote\Brittle\Tests;

use KrisLamote\Brittle\DateTimeField;
use KrisLamote\Brittle\Exception\RecordLengthException;
use KrisLamote\Brittle\FixedField;
use KrisLamote\Brittle\OffsetField;
use KrisLamote\Brittle\Reader;
use \Mockery as Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Class ReaderEncodingTest
 * @package KrisLamote\Brittle\Tests
 */
class ReaderEncodingTest extends TestCase
{
    public function testUtf8File()
    {
        $fields = [
            new OffsetField('encoded', 13, 5)
        ];
        $reader = Reader::fromString(file_get_contents(__DIR__.'/fixtures/utf8.txt'))
            ->withFields($fields)
            ->parse();

        $row = $reader->first();
        $this->assertEquals('hello', $row->encoded);

        $row = $reader->next();
        $this->assertEquals('hêllö', $row->encoded);
    }

    public function testIso8859File()
    {
        $fields = [
            new OffsetField('encoded', 4, 6)
        ];
        $reader = Reader::fromString(file_get_contents(__DIR__.'/fixtures/iso8859.txt'))
            ->withFields($fields)
            ->parse();

        $row = $reader->first();
        $this->assertEquals('België', $row->encoded);
    }

}