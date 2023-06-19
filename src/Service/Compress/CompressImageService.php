<?php

declare(strict_types=1);

namespace Dige\TinypngPlugin\Service\Compress;

use Dige\TinypngPlugin\Entity\Settings;
use Dige\TinypngPlugin\Repository\SettingsRepositoryInterface;
use Tinify\Source;
use Tinify\Tinify;

class CompressImageService implements CompressImageInterface
{
    private string $storagePath;
    private string $apiKey;
    private SettingsRepositoryInterface $settingsRepository;
    private array $availableMediaExtensions;

    private ?Settings $settings;

    public function __construct(string $storagePath, SettingsRepositoryInterface $settingsRepository, array $availableMediaExtensions)
    {
        $this->storagePath = $storagePath;
        $this->settingsRepository = $settingsRepository;
        $this->settings = $this->settingsRepository->findLast();
        $this->apiKey = $this->settings ? $this->settings->getApiKey() : '';
        $this->availableMediaExtensions = $availableMediaExtensions;
    }

    public function __invoke(string $path): void
    {
        Tinify::setKey($this->apiKey);

        $file = $this->storagePath . '/' . $path;

        if($this->isValid($file)) {
            try {
                Source::fromFile($file)->toFile($file);
            } catch (\Throwable $exception) {
                if($this->settings && str_contains($exception->getMessage(), 'exceeded')) {
                    $this->settings->setApiKeyLimitExceeded(true);
                    $this->settingsRepository->add($this->settings);
                }
                throw $exception;
            }
        }
    }

    private function isValid(string $path): bool
    {
        $ext = strtolower(explode('_', pathinfo($path, PATHINFO_EXTENSION))[0]);

        return file_exists($path) && in_array($ext, $this->availableMediaExtensions);
    }
}
