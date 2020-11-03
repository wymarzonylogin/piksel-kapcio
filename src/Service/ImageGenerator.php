<?php

declare(strict_types=1);

namespace WymarzonyLogin\PikselKapcio\Service;

use WymarzonyLogin\PikselKapcio\ImageGeneratorConfig;

class ImageGenerator
{   
    private $config;
    
    public function __construct(ImageGeneratorConfig $config)
    {
        $this->config = $config;
    }
    
    public function generateImageData()
    {
        $text = $this->getText();
        $scale = $this->config->getScale();
        $imageWidth = $scale * (7 * strlen($text));
        $imageHeight = $scale * 7;
        $image = imagecreatetruecolor($imageWidth, $imageHeight);
        $pixelMap = $this->generatePixelMapForText($text);
        
        foreach ($pixelMap as $x => $column) {
            foreach ($column as $y => $bit) {
                $factorRed = (int) base_convert(substr($bit, 0, 2), 16, 10);
                $factorGreen = (int) base_convert(substr($bit, 2, 2), 16, 10);
                $factorBlue = (int) base_convert(substr($bit, 4, 2), 16, 10);
                imagefilledrectangle($image, $x * $scale, $y * $scale, ($x + 1) * $scale, ($y + 1) * $scale, imagecolorallocate($image, $factorRed, $factorGreen, $factorBlue));
            }
        }
        
        return $image;
    }
    
    private function getText(): string
    {
        if ($this->config->getTextGenerationMode() == ImageGeneratorConfig::TEXT_GENERATION_FROM_LIST) {
            $string = $this->config->getWordsList()[random_int(0, count($this->config->getWordsList()) - 1)];
        } else {
            $string = $this->generateRandomString($this->config->getRandomTextLength());
        }

        return strtoupper($string);
    }
    
    private function generateRandomString(int $length): string
    {
        $characterSet = '0123456789abcdefghijklmnopqrstuvwxyz';
        
        return substr(str_shuffle($characterSet), 0, $length);
    }
    
    private function generatePixelMapForText(string $text): array
    {
        $pixelMap = $this->initEmptyPixelMap($text);
                
        foreach (str_split($text) as $characterIndex => $character) {
            $colorPair = $this->getColorPairForCharacterIndex($characterIndex);
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
    
    private function padCharacterMap($characterMap): array
    {
        array_push($characterMap, 0);
        array_unshift($characterMap, 0);
        
        return $characterMap;
    }
    
    private function initEmptyPixelMap(string $text): array
    {
        $pixelMap = [];
        
        for ($x = 0; $x <= (strlen($text) * 7); $x++) {
            $pixelMap[$x] = [];
            for ($y = 0; $y <= 7; $y++) {
                $pixelMap[$x][$y] = '000000';
            }
        }
        
        return $pixelMap;
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
            case 'G':
                return [15, 16, 19, 17, 15];
            case 'H':
                return [17, 17, 31, 17, 17];
            case 'I':
                return [4, 4, 4, 4, 4];
            case 'J':
                return [1, 1, 1, 17, 14];
            case 'K':
                return [17, 17, 30, 17, 17];
            case 'L':
                return [16, 16, 16, 16, 31];
            case 'M':
                return [10, 21, 21, 21, 21];
            case 'N':
                return [17, 25, 21, 19, 17];
            case 'O':
                return [14, 17, 17, 17, 14];
            case 'P':
                return [30, 17, 30, 16, 16];
            case 'Q':
                return [14, 17, 21, 19, 14];
            case 'R':
                return [30, 17, 30, 17, 17];
            case 'S':
                return [15, 16, 14, 1, 30];
            case 'T':
                return [31, 4, 4, 4, 4];
            case 'U':
                return [17, 17, 17, 17, 14];
            case 'V':
                return [17, 17, 10, 10, 4];
            case 'W':
                return [21, 21, 21, 21, 10];
            case 'X':
                return [17, 10, 4, 10, 17];
            case 'Y':
                return [17, 10, 4, 4, 4];
            case 'Z':
                return [31, 2, 4, 8, 31];
            case '0':
                return [14, 19, 21, 25, 14];
            case '1':
                return [4, 12, 4, 4, 14];
            case '2':
                return [14, 17, 6, 8, 31];
            case '3':
                return [14, 1, 6, 1, 14];
            case '4':
                return [17, 17, 31, 1, 1];
            case '5':
                return [31, 16, 30, 1, 30];
            case '6':
                return [14, 16, 30, 17, 14];
            case '7':
                return [31, 1, 2, 4, 8];
            case '8':
                return [14, 17, 14, 17, 14];
            case '9':
                return [14, 17, 15, 1, 14];
            case '.':
                return [0, 0, 0, 0, 4];
            case '-':
                return [0, 0, 31, 0, 0];
            case '+':
                return [4, 4, 31, 4, 4];
            case '/':
                return [1, 2, 4, 8, 16];
            case '*':
                return [21, 14, 4, 14, 21];
            case '=':
                return [0, 31, 0, 31, 0];
            default:
                return [0, 0, 0, 0, 0];
        }
    }
}