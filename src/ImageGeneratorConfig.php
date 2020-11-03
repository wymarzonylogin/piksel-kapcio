<?php

declare(strict_types=1);

namespace WymarzonyLogin\PikselKapcio;

class ImageGeneratorConfig
{   
    public const COLOR_PAIRS_ROTATION_RANDOM = 0;
    public const COLOR_PAIRS_ROTATION_SEQUENCE = 1;
    public const TEXT_GENERATION_RANDOM = 0;
    public const TEXT_GENERATION_FROM_LIST = 1;
    
    private $scale;
    private $colorPairs;
    private $colorPairsRotation;
    private $randomTextLength;
    private $textGenerationMode;
    private $wordsList;
    
    public function __construct()
    {
        $this->scale = 5;
        $this->colorPairsRotation = self::COLOR_PAIRS_ROTATION_RANDOM;
        $this->colorPairs = [
            ['FFFFFF', '00FF00'],
            ['FFFFFF', 'FF8800'],
            ['00FF00', 'FFFFFF'],
            ['FF8800', 'FFFFFF'],
            ['00FF00', 'FF8800'],
            ['FF8800', '00FF00'],
        ];
        $this->randomTextLength = 4;
        $this->textGenerationMode = self::TEXT_GENERATION_FROM_LIST;
        $this->wordsList = [
            'angry',
            'coyote',
            'cappucino',
            'moustache',
            'wacor',
            'wymarzony',
            'login',
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
    
    public function getRandomTextLength(): int
    {
        return $this->randomTextLength;
    }
    
    public function setRandomTextLength(int $randomTextLength)
    {
        if ($randomTextLength > 36) {
            throw new InvalidArgumentException();
        }
        
        return $this->randomTextLength = $randomTextLength;
    }
    
    public function getTextGenerationMode(): int
    {
        return $this->textGenerationMode;
    }
    
    public function setTextGenerationMode(int $mode)
    {
        return $this->textGenerationMode = $mode;
    }
    
    public function getWordsList(): array
    {
        return $this->wordsList;
    }
    
    public function setWordsList(array $wordsList)
    {
        return $this->wordsList = $wordsList;
    }
}