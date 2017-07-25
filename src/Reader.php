<?php

namespace KrisLamote\Brittle;

use Countable;
use KrisLamote\Brittle\Exception\MissingSystemCommandException;
use KrisLamote\Brittle\Exception\RecordLengthException;

/**
 * Class Reader
 *
 * Warning: awk is being used for the ease of parsing the
 * fixed with file to a csv. Effectively this locks the library
 * to a *nix type OS
 * Obviously to be replaced for more flexibility
 *
 * @package KrisLamote\Brittle
 */
class Reader implements Countable
{

    /**
     * @var resource
     */
    private $document;

    /**
     * @var resource
     */
    private $csv;

    /**
     * @var array
     */
    private $keys = [];

    /**
     * @var int
     */
    private $lineCount = 0;

    /**
     * @var int
     */
    private $recordLength = 0;

    /**
     * @var array
     */
    private $fields = [];

    /**
     * @var bool
     */
    private $fileChecked = false;

    /**
     * Convert the string to a stream
     *
     * fopen('php://temp', 'r+') only writes to disk beyond a certain
     * file size as long as there is nothing written to disk, we can't
     * use awk so currently using tmpfile() in favour of php://temp
     *
     * @param string $content the fix-width document as a string
     * @return Reader
     */
    public static function fromString(string $content): self
    {
        $instance = new static();

        if (!empty($content)) {
            $instance->document = tmpfile(); // see comments above
            fwrite($instance->document, $content);
        }

        $instance->fileChecks();

        return $instance;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function withField(Field $field)
    {
        $this->fields[] = $field;
        $this->keys[] = $field->getLabel();

        return $this;
    }

    /**
     * @param $fields
     * @return $this
     */
    public function withFields(array $fields)
    {
        $this->fields = [];

        foreach ($fields as $field) {
            $this->withField($field);
        }

        return $this;
    }

    /**
     * @return int
     */
    public function recordLength()
    {
        $this->fileChecks();

        return $this->recordLength;
    }

    /**
     * - parse according to field definitions
     * - store content in a csv file for easy access according to field definitions
     *
     * @return $this
     * @throws MissingSystemCommandException
     */
    public function parse()
    {
        $this->csv = tmpfile(); // file handle in readwrite (w+) mode

        fputcsv($this->csv, $this->keys);

        rewind($this->document);
        while (!feof($this->document)) {
            $inputRecord = fgets($this->document);

            $row = array_map(function ($field) use ($inputRecord) {
                return $field->parse($inputRecord);
            }, $this->fields);

            fputcsv($this->csv, $row);
        }

        return $this;
    }

    /**
     * @return object|void
     */
    public function first()
    {
        rewind($this->csv);
        fgetcsv($this->csv); // skip the header

        return $this->next();
    }

    /**
     * @return bool|string|void
     */
    public function next()
    {
        if (!feof($this->csv) && (($data = fgetcsv($this->csv)) !== false)) {
            if (!empty($data)) {
                $values = [];
                $stop = count($this->fields);
                for ($i = 0; $i < $stop; $i++) {
                    $values[$this->keys[$i]] = $data[$i];
                }

                $row = array_combine($this->keys, array_map('trim', $values));
                return (object) $row;
            }
        }

        return (object) [];
    }

    /**
     * @param string $path
     * @return $this
     */
    public function toCsv(string $path)
    {
        rewind($this->csv);

        $targetCsv = fopen($path, 'w');
        while (($data = fgetcsv($this->csv)) !== false) {
            if (!empty($data)) {
                $row = array_combine(
                    $this->keys,
                    array_map('trim', $data)
                );
                fputcsv($targetCsv, $row);
            }
        }

        return $this;
    }

    /**
     * properly clean up file pointers
     */
    public function __destruct()
    {
        $this->closeFileHandle('document')
             ->closeFileHandle('csv');
    }

    /**
     * Counts the rows in the document
     *
     * @return int
     */
    public function count()
    {
        $this->fileChecks();

        return $this->lineCount;
    }

    /**
     * @return $this
     * @throws RecordLengthException
     */
    private function fileChecks()
    {
        if ($this->fileChecked) {
            return $this;
        }

        // empty ? lineCount & recordLength defaults apply
        if (empty($this->document)) {
            $this->fileChecked = true;
            return $this;
        }

        rewind($this->document);
        while (!feof($this->document)) {
            $row = fgets($this->document);
            $this->lineCount++;
            $currentRecordLength = mb_strlen(preg_replace( "/\r|\n/", '', $row));

            if (empty($this->recordLength)) {
                $this->recordLength = $currentRecordLength;
                continue;
            }

            if ($this->recordLength != $currentRecordLength) {
                throw new RecordLengthException();
            }
        }

        $this->fileChecked = true;

        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    private function closeFileHandle(string $name)
    {
        if ($this->$name) {
            fclose($this->$name);
            $this->$name = null;
        }

        return $this;
    }

}