<?php

declare(strict_types=1);

namespace WymarzonyLogin\PikselKapcio;

class ImageGeneratorConfig
{   
    public const COLOR_PAIRS_ROTATION_RANDOM = 0;
    public const COLOR_PAIRS_ROTATION_SEQUENCE = 1;
    
    private $scale;
    private $colorPairs;
    private $colorPairsRotation;
    
    public function __construct()
    {
        $this->scale = 5;
        $this->colorPairsRotation = self::COLOR_PAIRS_ROTATION_RANDOM;
        $this->colorPairs = [
            ['CCCCCC', '888888'],
            ['888888', 'CCCCCC'],
            ['00CC00', '97EA97'],
            ['97EA97', '00CC00'],
            ['9797EA', '5C5CDE'],
            ['5C5CDE', '9797EA'],
            ['FF8800', 'FFCE97'],
            ['FFCE97', 'FF8800'],
        ];
    }
    
    public function getScale(): int
    {
        return $this->scale;
    }
    
    public function setScale(int $scale)
    {
        $this->scale = $scale;
    }
    
    public function getColorPairsRotation(): int
    {
        return $this->colorPairsRotation;
    }
    
    public function setColorPairsRotation(int $colorPairsRotation)
    {
        $this->colorPairsRotation = $colorPairsRotation;
    }
    
    public function getColorPairs(): array
    {
        return $this->colorPairs;
    }
    
    public function setColorPairs(array $colorPairs)
    {
        return $this->colorPairs = $colorPairs;
    }
}