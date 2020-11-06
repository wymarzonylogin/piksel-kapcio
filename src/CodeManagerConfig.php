<?php

declare(strict_types=1);

namespace WymarzonyLogin\PikselKapcio;

class CodeManagerConfig
{   
    public const DEFAULT_SESSION_KEY = '_wl_kapcio';
    
    public const TEXT_GENERATION_RANDOM = 0;
    public const TEXT_GENERATION_FROM_LIST = 1;
    
    private $sessionKey;
    private $randomTextLength;
    private $textGenerationMode;
    private $wordsList;
    
    public function __construct(string $sessionKey = null)
    {
        if (null !== $sessionKey) {
            $this->sessionKey = $sessionKey;
        } else {
            $this->sessionKey = self::DEFAULT_SESSION_KEY;
        }
        
        $this->randomTextLength = 4;
        $this->textGenerationMode = self::TEXT_GENERATION_RANDOM;
        $this->wordsList = [
            'angry',
            'capitol',
            'cappuccino',
            'coyote',
            'czomo',
            'dubi',
            'electra',
            'login',
            'moustache',
            'pterodakl',
            'smacznego',
            'wacor',
            'wymarzony',
        ];
    }
    
    public function getSessionKey(): string
    {
        return $this->sessionKey;
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