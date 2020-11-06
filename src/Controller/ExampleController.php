<?php

declare(strict_types=1);

namespace WymarzonyLogin\PikselKapcio\Controller;

use WymarzonyLogin\PikselKapcio\CodeManagerConfig;
use WymarzonyLogin\PikselKapcio\ImageGeneratorConfig;
use WymarzonyLogin\PikselKapcio\CodeManager;

class ExampleController
{
    public function serveImage()
    {
//        $codeConfig = new CodeManagerConfig();
//        $codeConfig->setTextGenerationMode(CodeManagerConfig::TEXT_GENERATION_FROM_LIST);
//        $codeConfig->setWordsList(['kopi', 'kapi', 'wons', 'dubi']);
//        $codeConfig->setRandomTextLength(4);
//        
//        $imageConfig = new ImageGeneratorConfig();
//        $imageConfig->setScale(10);
//        $imageConfig->setColorPairs([['FFCC00', 'FFFFFF'], ['FFFFFF', '00CC00']]);
//        $imageConfig->setColorPairsRotation(ImageGeneratorConfig::COLOR_PAIRS_ROTATION_RANDOM);
//        
//        $codeManager = new CodeManager($codeConfig, $imageConfig);
        $codeManager = new CodeManager();
        $code = $codeManager->generateCode();
        
        header('Content-Type: image/png');
        imagepng($code->getImageData());
    }
}