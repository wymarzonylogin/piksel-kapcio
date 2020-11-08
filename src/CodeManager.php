<?php

declare(strict_types=1);

namespace WymarzonyLogin\PikselKapcio;

use WymarzonyLogin\PikselKapcio\Code;
use WymarzonyLogin\PikselKapcio\CodeManagerConfig;
use WymarzonyLogin\PikselKapcio\ImageGeneratorConfig;
use WymarzonyLogin\PikselKapcio\Service\ImageGenerator;

class CodeManager
{   
    private $isSessionStarted;
    private $sessionKey;
    private $config;
    private $imageGenerator;

    public function __construct(?CodeManagerConfig $codeConfig = null, ?ImageGeneratorConfig $imageConfig = null)
    {
        if (null === $codeConfig) {
            $codeConfig = new CodeManagerConfig();
        }
        
        $this->isSessionStarted = false;
        $this->sessionKey = $codeConfig->getSessionKey();
        $this->config = $codeConfig;
        $this->imageGenerator = new ImageGenerator($imageConfig);
    }
    
    public function generateCode(): Code
    {
        $text = $this->getText();
        $imageData = $this->imageGenerator->generateImageData($text);
        
        $code = new Code($text, $imageData);
        $this->setCodeText($text);

        return $code;
    }
    
    public function validateCode(string $text, bool $caseInsensitive = true): bool
    {
        if ($caseInsensitive) {
            $text = strtoupper($text);
        }
        
        $isValid = $this->getCodeText() === $text && !empty($this->getCodeText());
        
        if ($isValid) {
            $this->unsetCodeText();
        }
        
        return $isValid;
    }
    
    private function unSetCodeText()
    {
        if (!$this->isSessionStarted) {
            $this->startSession();
        }
        
        unset($_SESSION[$this->sessionKey]);
    }
    
    private function setCodeText(string $text)
    {
        if (!$this->isSessionStarted) {
            $this->startSession();
        }
        
        $_SESSION[$this->sessionKey] = $text;
    }
    
    private function getCodeText(): ?string
    {
        if (!$this->isSessionStarted) {
            $this->startSession();
        }
        
        if (!isset($_SESSION[$this->sessionKey])) {
            return null;
        }
        
        return (string) $_SESSION[$this->sessionKey];
    }
    
    private function getText(): string
    {
        if ($this->config->getTextGenerationMode() == CodeManagerConfig::TEXT_GENERATION_FROM_LIST) {
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
    
    private function startSession()
    {
        if (\PHP_SESSION_NONE === session_status()) {
            session_start();
        }

        $this->isSessionStarted = true;
    }
}