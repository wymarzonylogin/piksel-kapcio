<?php

declare(strict_types=1);

namespace WymarzonyLogin\PikselKapcio\Controller;

use WymarzonyLogin\PikselKapcio\Service\PikselKapcio;

class PikselKapcioController
{
    public function serveImage()
    {
        $kapcioService = new PikselKapcio();
        $imageData = $kapcioService->generateImageData();
        
        header('Content-Type: image/png');
        imagepng($imageData);
    }
}