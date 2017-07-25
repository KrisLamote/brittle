<?php

namespace KrisLamote\Brittle;

/**
 * Class FixedField
 * Adapter implementation on top of OffsetField
 *
 * @package KrisLamote\Brittle
 */
class FixedField implements Field
{
    /**
     * @var
     */
    public $value = null;

    /**
     * @var OffsetField
     */
    private $offsetField;

    /**
     * FixedField constructor.
     * @param $label
     * @param null $value
     */
    public function __construct($label, $value = null)
    {
        $this->offsetField = new OffsetField($label, 1, 0);
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->offsetField->getLabel();
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