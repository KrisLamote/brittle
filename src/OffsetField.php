<?php

namespace KrisLamote\Brittle;

/**
 * Class OffsetField
 *
 * This is kind of the base field class, except that different types
 * of fields require a different amount of instance variables.
 * Instead of using a constructor which handles a variable amount of
 * variables, currently the other Field classes are implemented as
 * Adapters (iow using a OffsetField for delegating the base functionality
 * to and implementing its own specialisation features)
 *
 * @package KrisLamote\Brittle
 */
class OffsetField implements Field
{
    /**
     * @var string
     */
    private $label = '';

    /**
     * assuming 1-based indexing
     * @var int
     */
    public $offset = 1;

    /**
     * @var int
     */
    public $length = 1;

    /**
     * OffsetField constructor.
     * @param $label
     * @param int $offset
     * @param int $length
     */
    public function __construct($label, $offset = 1, $length = 1)
    {
        $this->label = $label;
        $this->offset = $offset;
        $this->length = $length;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param $input
     * @return string
     */
    public function parse(string $input)
    {
        return trim(mb_substr($input, $this->offset, $this->length));
    }

}