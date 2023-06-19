<?php

namespace Dige\TinypngPlugin\Service\Compress\Log;

use Dige\TinypngPlugin\Entity\MediaLog;
use Sylius\Component\Core\Model\ImageInterface;

interface CompressMediaLogServiceInterface
{
    public function createLog(ImageInterface $image): MediaLog;
    public function createLogForRawData(string $path, int $mediaId, string $entityClass): MediaLog;
    public function createCompressedLogForRawData(string $path, int $mediaId, string $entityClass): MediaLog;
    public function createCompressedLog(ImageInterface $image): MediaLog;
    public function createLogs(): void;
    public function getCompressedCount(): int;
    public function getUnCompressedCount(): int;
}
