<?php

declare(strict_types=1);

namespace WymarzonyLogin\PikselKapcio\Service;

class PikselKapcio
{   
    private $scale;
    private $bgColor;
    private $fgColor;
    private $colorPairs;
    
    public function __construct($fgColor = 'FF0000', $bgColor = 'AA9900', int $scale = 15)
    {
        $this->scale = $scale;
        $this->bgColor = $bgColor;
        $this->fgColor = $fgColor;
        $this->colorPairs = [
            [
                [255, 255, 255],
                [0, 255, 0],
                
            ],
            [
                [255, 255, 255],
                [255, 127, 0],
                
            ],
            [
                [0, 255, 0],
                [255, 255, 255],
                
            ],
            [
                [255, 127, 0],
                [255, 255, 255],
                
            ],
            [
                [0, 255, 0],
                [255, 127, 0],
            ],
            [
                [255, 127, 0],
                [0, 255, 0],
            ],
        ];
    }
    
    public function generateImageData()
    {
        $text = $this->getText();
        $imageWidth = $this->scale * (7 * strlen($text));
        $imageHeight = $this->scale * 7;
        
        $image = imagecreatetruecolor($imageWidth, $imageHeight);
        $bgColor = imagecolorallocate($image, 255, 0, 0);
        $fgColor = imagecolorallocate($image, 0, 255, 0);
        
        imagefilledrectangle ($image , 0, 0, $imageWidth - 1, $imageHeight - 1, $bgColor);
        
        $pixelMap = $this->generatePixelMapForText($text);
        
        foreach ($pixelMap as $x => $column) {
            foreach ($column as $y => $bit) {
                imagefilledrectangle($image, $x * $this->scale, $y * $this->scale, ($x + 1) * $this->scale, ($y + 1) * $this->scale, imagecolorallocate($image, $bit[0], $bit[1], $bit[2]));
            }
        }
        
        return $image;
    }
    
    private function getText(): string
    {
        $string = 'ABCDEF';
        
        return strtoupper($string);
    }
    
    private function getCharacterMap(string $character)
    {
        switch ($character) {
            case 'A':
                return [14, 17, 31, 17, 17];
            case 'B':
                return [30, 17, 30, 17, 30];
            case 'C':
                return [15, 16, 16, 16, 15];
            case 'D':
                return [30, 17, 17, 17, 30];
            case 'E':
                return [31, 16, 28, 16, 31];
            case 'F':
                return [31, 16, 28, 16, 16];
            case 'I':
                return [4, 4, 4, 4, 4];
            case 'K':
                return [17, 17, 30, 17, 17];
            case 'O':
                return [31, 17, 17, 17, 31];
            case 'P':
                return [31, 17, 31, 16, 16];
            default:
                return [0, 0, 0, 0, 0];
        }
    }
    
    private function initEmptyPixelMap(string $text): array
    {
        $pixelMap = [];
        
        for ($x = 0; $x <= (strlen($text) * 7); $x++) {
            $pixelMap[$x] = [];
            for ($y = 0; $y <= 7; $y++) {
                $pixelMap[$x][$y] = [0, 0, 0];
            }
        }
        
        return $pixelMap;
    }
    
    private function generatePixelMapForText(string $text): array
    {
        $pixelMap = $this->initEmptyPixelMap($text);
                
        foreach (str_split($text) as $characterIndex => $character) {
            $colorPair = $this->colorPairs[random_int(0, count($this->colorPairs) - 1)];
            $characterMap = $this->padCharacterMap($this->getCharacterMap($character));
            
            foreach ($characterMap as $lineIndex => $line) {
                $lineBitMap = str_split(str_pad(str_pad(decbin($line), 6, '0', STR_PAD_LEFT), 7, '0', STR_PAD_RIGHT));
                
                foreach ($lineBitMap as $bitOffset => $bit) {
                    $pixelMap[($characterIndex * 7) + $bitOffset][$lineIndex] = $colorPair[$bit];
                }
            }
        }
        
        return $pixelMap;
    }
    
    private function padCharacterMap($characterMap): array
    {
        array_push($characterMap, 0);
        array_unshift($characterMap, 0);
        
        return $characterMap;
    }
}