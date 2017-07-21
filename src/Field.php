<?php

namespace KrisLamote\Brittle;

/**
 * Class Field
 *
 * Defined how a certain field can be extracted
 * from a fixed width file
 *
 * Currently only supporting offset & length
 *
 * Because the Reader is (for the time being) using awk for parsing, the offset is 1-based
 * being taking offset 0 or 1 both start from the start of the string
 * See further comments in Reader as well
 *
 * awk substring: "..If start is less than one, substr() treats it as if it was one.."
 *
 * @package KrisLamote\Brittle
 */
class Field
{
    /**
     * @var string
     */
    public $label = '';

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
     * Field constructor.
     * @param string $label
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
    public function awkSubstr()
    {
        return "substr($0, {$this->offset}, {$this->length})";
    }

}