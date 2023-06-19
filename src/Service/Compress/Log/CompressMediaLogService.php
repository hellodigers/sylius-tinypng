<?php

namespace Dige\TinypngPlugin\Service\Compress\Log;

use Dige\TinypngPlugin\Entity\MediaLog;
use Dige\TinypngPlugin\Repository\MediaLogRepositoryInterface;
use Dige\TinypngPlugin\Service\Cache\CacheServiceInterface;
use Dige\TinypngPlugin\Service\Consts\CacheNames;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CompressMediaLogService implements CompressMediaLogServiceInterface
{
    private const BULK_SIZE_CREATE_MEDIA_LOG = 100;

    private MediaLogRepositoryInterface $logRepository;
    private ContainerInterface $container;
    private array $repositoriesIds;
    private CacheServiceInterface $cacheService;

    public function __construct(
        MediaLogRepositoryInterface $logRepository,
        ContainerInterface $container,
        array $repositoriesIds,
        CacheServiceInterface $cacheService)
    {
        $this->logRepository = $logRepository;
        $this->container = $container;
        $this->repositoriesIds = $repositoriesIds;
        $this->cacheService = $cacheService;
    }

    public function createLog(ImageInterface $image): MediaLog
    {
        $log = $this->create($image->getPath(), $image->getId(), get_class($image));
        $this->logRepository->add($log);

        return $log;
    }

    public function createCompressedLog(ImageInterface $image): MediaLog
    {
        $log = $this->create($image->getPath(), $image->getId(), get_class($image));
        $log->setCompressedAt(new \DateTime());
        $this->logRepository->add($log);

        return $log;
    }

    private function create(string $path, int $mediaId, string $entityClass): MediaLog
    {
        $log = new MediaLog();
        $log->setMediaId($mediaId);
        $log->setEntityClassName($entityClass);
        $log->setPath($path);

        return $log;
    }

    public function createLogs(): void
    {
        $mediaIds = $this->logRepository->getMediasId();

        $actualImagesCount = 0;
        $mediaLogCounter = 0;
        $overallMediaLogCount = 0;
        foreach ($this->repositoriesIds as $repositoriesId) {
            /** @var RepositoryInterface $repository */
            $repository = $this->container->get($repositoriesId);
            $images = $repository->findAll();

            $actualImagesCount += count($images);

            $this->cacheService->set(CacheNames::CACHE_KEY_OVERALL_FILE_COUNT, $actualImagesCount);

            $mediaLogCounter = 0;
            /** @var ImageInterface $image */
            foreach ($images as $image) {
                if(!in_array($image->getId(), $mediaIds)) {
                    $this->createLogForRawData($image->getPath(), $image->getId(), get_class($image));
                    $mediaLogCounter++;
                    if($mediaLogCounter > self::BULK_SIZE_CREATE_MEDIA_LOG) {
                        $overallMediaLogCount += $mediaLogCounter;
                        $this->cacheService->set(CacheNames::CACHE_KEY_CREATED_MEDIA_LOG_FOR_FILE_COUNT, $overallMediaLogCount);
                        $mediaLogCounter = 0;
                    }
                }
            }
            $overallMediaLogCount += $mediaLogCounter;
            $this->cacheService->set(CacheNames::CACHE_KEY_CREATED_MEDIA_LOG_FOR_FILE_COUNT, $overallMediaLogCount);
        }
    }

    public function getCompressedCount(): int
    {
        return $this->logRepository->getCompressedCount();
    }

    public function getUnCompressedCount(): int
    {
        return $this->logRepository->getUnCompressedCount();
    }

    public function createLogForRawData(string $path, int $mediaId, string $entityClass): MediaLog
    {
        $log = $this->create($path, $mediaId, $entityClass);
        $this->logRepository->add($log);

        return $log;
    }

    public function createCompressedLogForRawData(string $path, int $mediaId, string $entityClass): MediaLog
    {
        $log = $this->create($path, $mediaId, $entityClass);
        $log->setCompressedAt(new \DateTime());
        $this->logRepository->add($log);

        return $log;
    }
}
