<?php

namespace KrisLamote\Brittle;

/**
 * Class AbstractField
 * @package KrisLamote\Brittle
 */
abstract class AbstractField implements Field
{

    /**
     * @var string
     */
    private $label = '';

    /**
     * The label of the field, will become part of the header
     * @return string
     */
    public function getLabel()
    {
        return $this->label();
    }

    /**
     * AWK command to be used for extracting the field
     * @return string
     */
    public function awkSubstr()
    {
        // TODO: Implement awkSubstr() method.
    }
}