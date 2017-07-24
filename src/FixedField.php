<?php

namespace KrisLamote\Brittle;

/**
 * Class FixedField
 *
 * @package KrisLamote\Brittle
 */
class FixedField implements Field
{
    /**
     * @var string
     */
    private $label = '';

    /**
     * @var
     */
    public $value = null;

    /**
     * FixedField constructor.
     * @param $label
     * @param null $value
     */
    public function __construct($label, $value = null)
    {
        $this->label = $label;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function awkSubstr()
    {
        return "\"{$this->value}\"";
    }

    /**
     * @param $input
     * @return string
     */
    public function parse($input)
    {
        return trim($this->value);
    }

}