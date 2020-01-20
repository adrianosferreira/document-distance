<?php

namespace AdrianoFerreira\DD;

class File extends DocumentDistance
{
    private const BUFFER_LENGTH = 4096;

    private $file1;
    private $file2;

    public function __construct($file1, $file2)
    {
        [$this->file1, $this->file2] = $this->getFilesData($file1, $file2);
    }

    protected function getText1()
    {
        return $this->file1;
    }

    protected function getText2()
    {
        return $this->file2;
    }

    private function getFilesData($file1, $file2)
    {
        if ( ! file_exists($file1)) {
            throw new \BadMethodCallException('File 1 not found');
        }

        if ( ! file_exists($file2)) {
            throw new \BadMethodCallException('File 2 not found');
        }

        $file1Handle = fopen($file1, 'r');
        $file2Handle = fopen($file2, 'r');

        $f1Data = '';
        $f2Data = '';

        $buffer1 = fgets($file1Handle, self::BUFFER_LENGTH);
        $buffer2 = fgets($file2Handle, self::BUFFER_LENGTH);

        while ($buffer1 || $buffer2) {
            $f1Data .= $buffer1;
            $f2Data .= $buffer2;

            $buffer1 = fgets($file1Handle, self::BUFFER_LENGTH);
            $buffer2 = fgets($file2Handle, self::BUFFER_LENGTH);
        }

        return [$f1Data, $f2Data];
    }
}