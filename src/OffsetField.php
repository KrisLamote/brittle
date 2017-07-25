<?php

namespace KrisLamote\Brittle;

/**
 * Class OffsetField
 *
 * Because the Reader is (for the time being) using awk for parsing, the offset is 1-based
 * being taking offset 0 or 1 both start from the start of the string
 * See further comments in Reader as well
 *
 * awk substring: "..If start is less than one, substr() treats it as if it was one.."
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
     * @return string
     */
    public function awkSubstr()
    {
        return "substr($0, {$this->offset}, {$this->length})";
    }

    /**
     * @param $input
     * @return string
     */
    public function parse($input)
    {
        return trim(substr($input, $this->offset, $this->length));
    }

}