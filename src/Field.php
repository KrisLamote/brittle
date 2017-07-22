<?php

namespace KrisLamote\Brittle;

/**
 * Interface Field
 *
 * Defined how a certain field can be extracted
 * and labeled from a fixed width file
 *
 * @package KrisLamote\Brittle
 */
interface Field
{
    /**
     * The label of the field, will become part of the header
     * @return string
     */
    public function getLabel();

    /**
     * AWK command to be used for extracting the field
     * @return string
     */
    public function awkSubstr();

}