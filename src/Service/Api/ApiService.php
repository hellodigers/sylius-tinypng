<?php

declare(strict_types=1);

namespace Dige\TinypngPlugin\Service\Api;

use Dige\TinypngPlugin\Repository\SettingsRepositoryInterface;
use Tinify\Tinify;

class ApiService implements ApiServiceInterface
{
    private ?string $apiKey;

    public function __construct(SettingsRepositoryInterface $settingsRepository)
    {
        $settings = $settingsRepository->findLast();
        $this->apiKey = $settings ? $settings->getApiKey() : null;
    }

    public function getCount(): int
    {
        if (!$this->apiKey) {
            return 0;
        }

        Tinify::setKey($this->apiKey);

        return (int)Tinify::getCompressionCount();
    }
}
