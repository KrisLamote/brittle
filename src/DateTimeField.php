<?php

namespace KrisLamote\Brittle;

use DateTime;

/**
 * Class DateTimeField
 * Adapter implementation on top of OffsetField
 * The format string is NOT validated
 *
 * @package KrisLamote\Brittle
 */
class DateTimeField implements Field
{
    /**
     * any valid DateTime format
     * @var string
     */
    private $outFormat = 'Y-m-d';

    /**
     * any valid DateTime format OR anything empty-ish
     * @var string
     */
    private $inFormat = '';

    /**
     * @var OffsetField
     */
    private $offsetField;

    /**
     * DateTimeField constructor.
     * @param $label
     * @param int $offset
     * @param int $length
     * @param string $outFormat
     */
    public function __construct($label, $offset = 1, $length = 1, $outFormat = 'Y-m-d')
    {
        $this->offsetField = new OffsetField($label, $offset, $length);
        $this->outFormat = $outFormat;
    }

    /**
     * @param string $format
     */
    public function setOutputFormat(string $format)
    {
        $this->outFormat = $format;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->offsetField->getLabel();
    }

    /**
     * @param $input
     * @return string
     */
    public function parse(string $input)
    {
        $input = $this->offsetField->parse($input);

        return (new DateTime($input))->format($this->outFormat);
    }

}