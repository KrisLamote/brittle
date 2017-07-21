<?php

namespace KrisLamote\Brittle\Tests;

use KrisLamote\Brittle\Field;
use KrisLamote\Brittle\Reader;
use PHPUnit\Framework\TestCase;

/**
 * Class FieldTest
 * @package KrisLamote\Brittle\Tests
 */
class FieldTest extends TestCase
{

    /**
     * @test Can be instantiated
     */
    public function canBeInstantiated()
    {
        $field = new Field('bar', 2, 4);

        $this->assertInstanceOf(Field::class, $field);
        $this->assertEquals('bar', $field->label);
        $this->assertEquals(2, $field->offset);
        $this->assertEquals(4, $field->length);
    }

    /**
     * @test Can be instantiated with minimal amount of arguments
     */
    public function canBeInstantiatedWithDefaults()
    {
        $field = new Field('foo');

        $this->assertInstanceOf(Field::class, $field);
        $this->assertEquals('foo', $field->label);
        $this->assertEquals(1, $field->offset);
        $this->assertEquals(1, $field->length);
    }

    /**
     * @test transform to required portion of for an awk parsing command
     * @see Reader
     */
    public function canConvertToAwkSubstringCommand()
    {
        $field = new Field('bar', 2, 4);

        $this->assertEquals('substr($0, 2, 4)', $field->awkSubstr());
    }

}