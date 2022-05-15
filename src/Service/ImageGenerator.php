<?php

declare(strict_types=1);

namespace WymarzonyLogin\PikselKapcio\Service;

use WymarzonyLogin\PikselKapcio\ImageGeneratorConfig;

class ImageGenerator
{   
    private $config;
    private $colorFactorCache;
    
    public function __construct(?ImageGeneratorConfig $config = null)
    {
        if (null === $config) {
            $config = new ImageGeneratorConfig();
        }
        
        $this->config = $config;
        $this->colorFactorCache = [];
    }
    
    public function generateImageData(string $text)
    {
        $scale = $this->config->getScale();
        $imageWidth = $scale * (7 * strlen($text));
        $imageHeight = $scale * 7;
        $image = imagecreatetruecolor($imageWidth, $imageHeight);
        $pixelMap = $this->generatePixelMapForText($text);
        
        foreach ($pixelMap as $x => $column) {
            foreach ($column as $y => $bit) {
                if (!isset($this->colorFactorCache[$bit])) {
                    $this->colorFactorCache[$bit] = [
                        (int) base_convert(substr($bit, 0, 2), 16, 10),
                        (int) base_convert(substr($bit, 2, 2), 16, 10),
                        (int) base_convert(substr($bit, 4, 2), 16, 10),
                    ];
                }
                
                imagefilledrectangle($image, $x * $scale, $y * $scale, ($x + 1) * $scale, ($y + 1) * $scale, imagecolorallocate($image, $this->colorFactorCache[$bit][0], $this->colorFactorCache[$bit][1], $this->colorFactorCache[$bit][2]));
            }
        }
        
        return $image;
    }
    
    private function generatePixelMapForText(string $text): array
    {
        $pixelMap = array_fill(0, strlen($text) * 7, array_fill(0, 7, '000000'));
                
        foreach (str_split($text) as $characterIndex => $character) {
            $colorPair = $this->getColorPairForCharacterIndex($characterIndex);
            $characterMap = $this->getCharacterMap($character);
                    
            foreach ($characterMap as $lineIndex => $line) {
                $lineBitMap = str_split(str_pad(str_pad(decbin($line), 6, '0', STR_PAD_LEFT), 7, '0', STR_PAD_RIGHT));
                
                foreach ($lineBitMap as $bitOffset => $bit) {
                    $pixelMap[($characterIndex * 7) + $bitOffset][$lineIndex] = $colorPair[$bit];
                }
            }
        }
        
        return $pixelMap;
    }
    
    private function getColorPairForCharacterIndex(int $index)
    {
        $colorPairs = $this->config->getColorPairs();
                
        switch ($this->config->getColorPairsRotation()) {
            case ImageGeneratorConfig::COLOR_PAIRS_ROTATION_SEQUENCE:
                return $colorPairs[$index % count($colorPairs)];
            default:
                return $colorPairs[random_int(0, count($colorPairs) - 1)];
        }
    }
    
    private function getCharacterMap(string $character)
    {
        switch ($character) {
            case 'A':
                return [0, 14, 17, 31, 17, 17, 0];
            case 'B':
                return [0, 30, 17, 30, 17, 30, 0];
            case 'C':
                return [0, 15, 16, 16, 16, 15, 0];
            case 'D':
                return [0, 30, 17, 17, 17, 30, 0];
            case 'E':
                return [0, 31, 16, 28, 16, 31, 0];
            case 'F':
                return [0, 31, 16, 28, 16, 16, 0];
            case 'G':
                return [0, 15, 16, 19, 17, 15, 0];
            case 'H':
                return [0, 17, 17, 31, 17, 17, 0];
            case 'I':
                return [0, 4, 4, 4, 4, 4, 0];
            case 'J':
                return [0, 1, 1, 1, 17, 14, 0];
            case 'K':
                return [0, 17, 17, 30, 17, 17, 0];
            case 'L':
                return [0, 16, 16, 16, 16, 31, 0];
            case 'M':
                return [0, 10, 21, 21, 21, 21, 0];
            case 'N':
                return [0, 17, 25, 21, 19, 17, 0];
            case 'O':
                return [0, 14, 17, 17, 17, 14, 0];
            case 'P':
                return [0, 30, 17, 30, 16, 16, 0];
            case 'Q':
                return [0, 14, 17, 21, 19, 14, 0];
            case 'R':
                return [0, 30, 17, 30, 17, 17, 0];
            case 'S':
                return [0, 15, 16, 14, 1, 30, 0];
            case 'T':
                return [0, 31, 4, 4, 4, 4, 0];
            case 'U':
                return [0, 17, 17, 17, 17, 14, 0];
            case 'V':
                return [0, 17, 17, 10, 10, 4, 0];
            case 'W':
                return [0, 21, 21, 21, 21, 10, 0];
            case 'X':
                return [0, 17, 10, 4, 10, 17, 0];
            case 'Y':
                return [0, 17, 10, 4, 4, 4, 0];
            case 'Z':
                return [0, 31, 2, 4, 8, 31, 0];
            case '0':
                return [0, 14, 19, 21, 25, 14, 0];
            case '1':
                return [0, 4, 12, 4, 4, 14, 0];
            case '2':
                return [0, 14, 17, 6, 8, 31, 0];
            case '3':
                return [0, 14, 1, 6, 1, 14, 0];
            case '4':
                return [0, 17, 17, 31, 1, 1, 0];
            case '5':
                return [0, 31, 16, 30, 1, 30, 0];
            case '6':
                return [0, 14, 16, 30, 17, 14, 0];
            case '7':
                return [0, 31, 1, 2, 4, 8, 0];
            case '8':
                return [0, 14, 17, 14, 17, 14, 0];
            case '9':
                return [0, 14, 17, 15, 1, 14, 0];
            case '.':
                return [0, 0, 0, 0, 0, 4, 0];
            case '+':
                return [0, 4, 4, 31, 4, 4, 0];
            case '-':
                return [0, 0, 0, 31, 0, 0, 0];
            case '*':
                return [0, 21, 14, 4, 14, 21, 0];
            case '/':
                return [0, 1, 2, 4, 8, 16, 0];
            case '=':
                return [0, 0, 31, 0, 31, 0, 0];
            case '?':
                return [0, 14, 17, 6, 0, 4, 0];
            case '(':
                return [0, 2, 4, 4, 4, 2, 0];
            case ')':
                return [0, 8, 4, 4, 4, 8, 0];
            default:
                return [0, 0, 0, 0, 0, 0, 0];
        }
    }
}