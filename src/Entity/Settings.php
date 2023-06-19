<?php

declare(strict_types=1);

namespace Dige\TinypngPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;

class Settings implements ResourceInterface
{
    use TimestampableTrait, ToggleableTrait;

    private ?int $id;
    private string $apiKey;

    private bool $apiKeyLimitExceeded;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    public function isApiKeyLimitExceeded(): bool
    {
        return $this->apiKeyLimitExceeded;
    }

    public function setApiKeyLimitExceeded(bool $apiKeyLimitExceeded): void
    {
        $this->apiKeyLimitExceeded = $apiKeyLimitExceeded;
    }
}
