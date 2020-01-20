<?php

namespace AdrianoFerreira\DD;

class Text extends DocumentDistance
{
    private $text1;
    private $text2;

    public function __construct($text1, $text2)
    {
        $this->text1 = $text1;
        $this->text2 = $text2;
    }

    protected function getText1()
    {
        return $this->text1;
    }

    protected function getText2()
    {
        return $this->text2;
    }
}