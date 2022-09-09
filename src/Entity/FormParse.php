<?php

namespace App\Entity;

use App\Repository\FormParseRepository;

class FormParse
{
    protected string $inputText;
    protected string $outputText;

    public function getInputText(): string
    {
        return $this->inputText;
    }

    public function setInputText(string $inputText): void
    {
        $this->inputText = $inputText;
    }

    public function getOutputText(): string
    {
        return $this->outputText;
    }

    public function setOutputText(string $outputText): void
    {
        $this->outputText = $outputText;
    }



}