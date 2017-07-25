<?php

namespace KrisLamote\Brittle\Tests;

use KrisLamote\Brittle\Field;
use KrisLamote\Brittle\FixedField;
use KrisLamote\Brittle\Reader;
use PHPUnit\Framework\TestCase;

/**
 * Class FixedFieldTest
 * @package KrisLamote\Brittle\Tests
 */
class FixedFieldTest extends TestCase
{

    /**
     * @test Can be instantiated
     */
    public function canBeInstantiated()
    {
        $field = new FixedField('foo', 'bar');

        $this->assertInstanceOf(Field::class, $field);
        $this->assertInstanceOf(FixedField::class, $field);
        $this->assertEquals('foo', $field->getLabel());
        $this->assertEquals('bar', $field->value);
    }

    /**
     * @test Can be instantiated with minimal amount of arguments
     */
    public function canBeInstantiatedWithDefaults()
    {
        $field = new FixedField('foo');

        $this->assertInstanceOf(FixedField::class, $field);
        $this->assertEquals('foo', $field->getLabel());
        $this->assertEmpty($field->value);
    }

}