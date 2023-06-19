<?php

declare(strict_types=1);

namespace Dige\TinypngPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

class MediaLog implements ResourceInterface
{
    private ?int $id;
    private int $mediaId;
    private string $entityClassName;
    private ?\DateTimeInterface $compressedAt;
    private string $path;

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getMediaId(): int
    {
        return $this->mediaId;
    }

    public function setMediaId(int $mediaId): void
    {
        $this->mediaId = $mediaId;
    }

    public function getEntityClassName(): string
    {
        return $this->entityClassName;
    }

    public function setEntityClassName(string $entityClassName): void
    {
        $this->entityClassName = $entityClassName;
    }

    public function getCompressedAt(): ?\DateTimeInterface
    {
        return $this->compressedAt;
    }

    public function setCompressedAt(?\DateTimeInterface $compressedAt): void
    {
        $this->compressedAt = $compressedAt;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }
}
