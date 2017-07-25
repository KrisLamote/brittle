<?php

namespace KrisLamote\Brittle\Tests;

use KrisLamote\Brittle\Field;
use KrisLamote\Brittle\OffsetField;
use KrisLamote\Brittle\Reader;
use PHPUnit\Framework\TestCase;

/**
 * Class OffsetFieldTest
 * @package KrisLamote\Brittle\Tests
 */
class OffsetFieldTest extends TestCase
{

    /**
     * @test Can be instantiated
     */
    public function canBeInstantiated()
    {
        $field = new OffsetField('bar', 2, 4);

        $this->assertInstanceOf(Field::class, $field);
        $this->assertInstanceOf(OffsetField::class, $field);
        $this->assertEquals('bar', $field->getLabel());
        $this->assertEquals(2, $field->offset);
        $this->assertEquals(4, $field->length);
    }

    /**
     * @test Can be instantiated with minimal amount of arguments
     */
    public function canBeInstantiatedWithDefaults()
    {
        $field = new OffsetField('foo');

        $this->assertInstanceOf(OffsetField::class, $field);
        $this->assertEquals('foo', $field->getLabel());
        $this->assertEquals(1, $field->offset);
        $this->assertEquals(1, $field->length);
    }

    /**
     * @test transform to required portion of for an awk parsing command
     * @see Reader
     */
    public function canConvertToAwkSubstringCommand()
    {
        $field = new OffsetField('bar', 2, 4);

        $this->assertEquals('substr($0, 2, 4)', $field->awkSubstr());
    }

    /**
     * @test transform to required portion of for an awk parsing command
     * @see Reader
     */
    public function canParseFromAString()
    {
        $field = new OffsetField('bar', 1, 5);

        $this->assertEquals('hello', $field->parse('-hello-------'));
        $this->assertEquals('hell', $field->parse('-hell'));
        $this->assertEquals('hell', $field->parse('  hell       '));
        $this->assertEquals('', $field->parse(''));
    }

}