<?php

declare(strict_types=1);

namespace Dige\TinypngPlugin\Service\Compress;

use Dige\TinypngPlugin\Entity\Log;
use Dige\TinypngPlugin\Entity\MediaLog;
use Dige\TinypngPlugin\Repository\LogRepositoryInterface;
use Dige\TinypngPlugin\Repository\MediaLogRepositoryInterface;
use Dige\TinypngPlugin\Service\Cache\CacheServiceInterface;
use Dige\TinypngPlugin\Service\Consts\CacheNames;

class CompressImagesService implements CompressImagesInterface
{
    private const BULK_SIZE_SET_CACHE = 100;

    private CompressImageService $compressImageService;
    private LogRepositoryInterface $logRepository;
    private MediaLogRepositoryInterface $mediaLogRepository;
    private CacheServiceInterface $cacheService;

    public function __construct(
        CompressImageService        $compressImageService,
        LogRepositoryInterface      $logRepository,
        MediaLogRepositoryInterface $mediaLogRepository,
        CacheServiceInterface $cacheService)
    {
        $this->compressImageService = $compressImageService;
        $this->logRepository = $logRepository;
        $this->mediaLogRepository = $mediaLogRepository;
        $this->cacheService = $cacheService;
    }

    public function compressAll(): void
    {
        $now = new \DateTime();
        $log = $this->logRepository->findUnfinished();
        $mediaLogs = $this->mediaLogRepository->getUnCompressed();

        $this->saveCacheInfo(CacheNames::CACHE_KEY_UNCOMPRESSED_COUNT, count($mediaLogs));

        $counter = 0;
        try {
            /** @var MediaLog $mediaLog */
            foreach ($mediaLogs as $mediaLog) {
                ($this->compressImageService)($mediaLog->getPath());
                $mediaLog->setCompressedAt($now);
                $this->mediaLogRepository->add($mediaLog);
                $log->setCount($log->getCount() + 1);
                $counter++;

                if($counter > self::BULK_SIZE_SET_CACHE) {
                    $counter = 0;
                    $this->saveCacheInfo(CacheNames::CACHE_KEY_COMPRESSED_COUNT, $log->getCount());
                }
            }
            $this->saveCacheInfo(CacheNames::CACHE_KEY_COMPRESSED_COUNT, $log->getCount());
        } catch (\Throwable $e) {
            $this->saveLog($log, $now, $e->getMessage());

            throw $e;
        }

        $this->saveLog($log, $now);
    }

    private function saveLog(Log $log, \DateTime $now, ?string $exceptionMessage = null): void
    {
        $log->setFinishedAt($now);
        $log->setExceptionMessage($exceptionMessage);
        $this->logRepository->add($log);

        $this->saveCacheInfo(CacheNames::CACHE_KEY_COMPRESSED_COUNT, $log->getCount());
    }

    private function saveCacheInfo(string $cacheKey, int $value)
    {
        $this->cacheService->set($cacheKey, $value);
    }
}
