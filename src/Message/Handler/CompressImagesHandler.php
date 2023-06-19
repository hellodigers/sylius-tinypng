<?php

declare(strict_types=1);

namespace Dige\TinypngPlugin\Message\Handler;

use Dige\TinypngPlugin\Message\CompressImages;
use Dige\TinypngPlugin\Service\Compress\CompressImagesInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CompressImagesHandler implements MessageHandlerInterface
{
    private CompressImagesInterface $compressImagesService;

    public function __construct(CompressImagesInterface $compressImagesService)
    {
        $this->compressImagesService = $compressImagesService;
    }

    public function __invoke(CompressImages $compressImages)
    {
        try {
            $this->compressImagesService->compressAll();
        } catch (\Throwable $exception) {

        }
    }
}
