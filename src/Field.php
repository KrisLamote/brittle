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
     * @param string $input
     * @return string
     */
    public function parse(string $input);

}