<?php

declare(strict_types=1);

namespace WymarzonyLogin\PikselKapcio\Controller;

use WymarzonyLogin\PikselKapcio\ImageGeneratorConfig;
use WymarzonyLogin\PikselKapcio\Service\ImageGenerator;

class PikselKapcioController
{
    public function serveImage()
    {
        $config = new ImageGeneratorConfig();
        $config->setScale(5);
//        $config->setColorPairs([['FFCC00', 'FFFFFF'], ['FFFFFF', '00CC00']]);
        $config->setColorPairsRotation(ImageGeneratorConfig::COLOR_PAIRS_ROTATION_RANDOM);
        $config->setTextGenerationMode(ImageGeneratorConfig::TEXT_GENERATION_RANDOM);
        $config->setRandomTextLength(4);
        
        $kapcioService = new ImageGenerator($config);
        $imageData = $kapcioService->generateImageData();
        
        header('Content-Type: image/png');
        imagepng($imageData);
    }
}