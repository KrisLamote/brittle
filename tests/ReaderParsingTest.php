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
 * Class ReaderParsingTest
 * @package KrisLamote\Brittle\Tests
 */
class ReaderParsingTest extends TestCase
{
    /**
     * @test
     */
    public function parsesMultipleFields()
    {
        $fields = [
            new OffsetField('offset', 14, 3),
            new FixedField('fixed', 'fixed'),
            new DateTimeField('date_iso', 3, 8),
            new DateTimeField('date_words', 3, 8, 'j/M/Y')
        ];
        $reader = Reader::fromString(file_get_contents(__DIR__.'/fixtures/input.txt'))
            ->withFields($fields)
            ->parse();

        $row = $reader->first();
        $this->assertEquals('123', $row->offset);
        $this->assertEquals('fixed', $row->fixed);
        $this->assertEquals('2017-07-24', $row->date_iso);
        $this->assertEquals('24/Jul/2017', $row->date_words);

        $row = $reader->next();
        $this->assertEquals('234', $row->offset);
        $this->assertEquals('fixed', $row->fixed);
        $this->assertEquals('2017-08-01', $row->date_iso);
        $this->assertEquals('1/Aug/2017', $row->date_words);
    }

}