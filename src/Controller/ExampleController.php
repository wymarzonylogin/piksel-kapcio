<?php

declare(strict_types=1);

namespace WymarzonyLogin\PikselKapcio\Controller;

use WymarzonyLogin\PikselKapcio\CodeManager;
use WymarzonyLogin\PikselKapcio\CodeManagerConfig;
use WymarzonyLogin\PikselKapcio\ImageGeneratorConfig;

class ExampleController
{    
    public function serveImage()
    {
        $codeManager = new CodeManager();
        $code = $codeManager->generateCode();
        
        header('Content-Type: image/png');
        imagepng($code->getImageData());
    }
    
    public function serveCustomizedImage()
    {
        $codeConfig = new CodeManagerConfig('your_custom_session_key');
        $codeConfig->setTextGenerationMode(CodeManagerConfig::TEXT_GENERATION_FROM_LIST);
        $codeConfig->setWordsList(['made-in-berlin', 'test', 'your', 'words']);
        
        $imageConfig = new ImageGeneratorConfig();
        $imageConfig->setScale(7);
        $imageConfig->setColorPairsRotation(ImageGeneratorConfig::COLOR_PAIRS_ROTATION_SEQUENCE);
        $imageConfig->setColorPairs([['000000', 'AAAAAA'], ['FF0000', 'AAAAAA'], ['FFFF00', 'AAAAAA']]);
        
        $codeManager = new CodeManager($codeConfig, $imageConfig);
        $code = $codeManager->generateCode();
        
        header('Content-Type: image/png');
        imagepng($code->getImageData());
    }
    
    public function validateSubmittedForm()
    {
        $userSolution = $_POST['captcha_solution'];
        
        //if you set custom session key on code generation
        $codeConfig = new CodeManagerConfig('your_custom_session_key');
        $codeManager = new CodeManager($codeConfig);
        //otherwise
        $codeManager = new CodeManager();
        
        if ($codeManager->validateCode($userSolution)) {
            //all good, process the form
        } else {
            //invalid captcha solution, show error in form etc.
        }
    }
}