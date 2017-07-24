<?php

namespace KrisLamote\Brittle\Tests;

use KrisLamote\Brittle\DateTimeField;
use KrisLamote\Brittle\Field;
use PHPUnit\Framework\TestCase;

/**
 * Class DateTimeField
 * @package KrisLamote\Brittle\Tests
 */
class DateTimeFieldTest extends TestCase
{

    /**
     * @test Can be instantiated
     */
    public function canBeInstantiated()
    {
        $field = new DateTimeField('birthday', 2, 8);

        $this->assertInstanceOf(Field::class, $field);
        $this->assertInstanceOf(DateTimeField::class, $field);
    }

    /**
     * @test Formats the output with the provided DateTime format string
     */
    public function formatsOutputAsDesired()
    {
        $inputDateString = '19720428';

        $field = new DateTimeField('birthday', 2, 8);

        $this->assertEquals('1972-04-28', $field->parse($inputDateString));

        $field->setOutputFormat('M j');
        $this->assertEquals('Apr 28', $field->parse($inputDateString));
    }

}