<?php

declare(strict_types=1);

namespace WymarzonyLogin\PikselKapcio;

class Code
{   
    private $text;
    private $imageData;
    
    public function __construct(string $text, $imageData)
    {
        $this->setText($text);
        $this->setImageData($imageData);
    }
   
    public function getText(): string
    {
        return $this->text;
    }
    
    public function setText(string $text)
    {
        $this->text = $text;
    }
    
    public function getImageData()
    {
        return $this->imageData;
    }
    
    public function setImageData($imageData)
    {
        $this->imageData = $imageData;
    }
}