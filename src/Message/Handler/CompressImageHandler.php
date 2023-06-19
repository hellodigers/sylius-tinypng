<?php

declare(strict_types=1);

namespace Dige\TinypngPlugin\Message\Handler;

use Dige\TinypngPlugin\Message\CompressImage;
use Dige\TinypngPlugin\Service\Compress\CompressImageInterface;
use Dige\TinypngPlugin\Service\Compress\Log\CompressMediaLogServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\ImageInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CompressImageHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $em;
    private CompressImageInterface $compressImageService;
    private CompressMediaLogServiceInterface $compressMediaLogService;

    public function __construct(EntityManagerInterface $em, CompressImageInterface $compressImageService, CompressMediaLogServiceInterface $compressMediaLogService)
    {
        $this->em = $em;
        $this->compressImageService = $compressImageService;
        $this->compressMediaLogService = $compressMediaLogService;
    }

    public function __invoke(CompressImage $compressImage)
    {
        /** @var ImageInterface $image */
        $image = $this->em->find($compressImage->entityImageClass, $compressImage->entityId);
        if($image) {
            try {
                ($this->compressImageService)($image->getPath());
                $this->compressMediaLogService->createCompressedLogForRawData($image->getPath(), $image->getId(), get_class($image));
            } catch (\Throwable $exception) {
                throw new UnrecoverableMessageHandlingException($exception->getMessage());
            }
        }
    }
}
