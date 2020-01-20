<?php

namespace AdrianoFerreira\DD;

abstract class DocumentDistance
{
    abstract protected function getText1();

    abstract protected function getText2();

    public function getPercent(): int
    {
        $arcSize = $this->getArcSize();

        return 100 - (int)(($arcSize * 100) / 1.57);
    }

    public function getArcSize(): float
    {
        $s1Frequency = $this->getFrequency($this->getText1());
        $s2Frequency = $this->getFrequency($this->getText2());

        $numerator   = $this->innerProduct($s1Frequency, $s2Frequency);
        $denominator = sqrt($this->innerProduct($s1Frequency, $s1Frequency)
            * $this->innerProduct($s2Frequency, $s2Frequency));

        return acos($numerator / $denominator);
    }

    private function innerProduct(array $l1, array $l2): float
    {
        $sum = 0.0;
        foreach ($l1 as $key => $val) {
            foreach ($l2 as $key2 => $val2) {
                if ($key === $key2) {
                    $sum += $val * $val2;
                }
            }
        }

        return $sum;
    }

    private function getFrequency($s): array
    {
        $table = [];
        $temp  = '';
        $len   = strlen($s);

        for ($i = 0; $i <= $len; $i++) {

            if ($temp
                && ( ! isset($s[$i]) || $s[$i] === ' '
                    || $s[$i] === PHP_EOL
                    || $i === strlen($s))
            ) {

                if ( ! isset($table[$temp])) {
                    $table[$temp] = 0;
                }

                $table[$temp]++;
                $temp = '';
                continue;
            }

            if (in_array($s[$i], ['!', '.', ';', ',', ':', '?'])) {
                continue;
            }

            if ($s[$i] !== ' ') {
                $temp .= $s[$i];
            }
        }

        return $table;
    }
}