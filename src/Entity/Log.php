<?php

declare(strict_types=1);

namespace Dige\TinypngPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

class Log implements ResourceInterface
{
    protected ?\DateTimeInterface $createdAt;

    private ?\DateTimeInterface $finishedAt;

    private string $username;

    private int $count = 0;

    private ?int $id;

    private ?string $exceptionMessage;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->finishedAt = null;
        $this->exceptionMessage = null;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getFinishedAt(): ?\DateTimeInterface
    {
        return $this->finishedAt;
    }

    public function setFinishedAt(?\DateTimeInterface $finishedAt): void
    {
        $this->finishedAt = $finishedAt;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function isFinished(): bool
    {
        return (bool)$this->getFinishedAt();
    }

    public function getExceptionMessage(): ?string
    {
        return $this->exceptionMessage;
    }

    public function setExceptionMessage(?string $exceptionMessage): void
    {
        $this->exceptionMessage = $exceptionMessage;
    }
}
