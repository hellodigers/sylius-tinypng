<?php

namespace Dige\TinypngPlugin\Repository;

use Sylius\Component\Resource\Repository\RepositoryInterface;

interface MediaLogRepositoryInterface extends RepositoryInterface
{
    public function getCompressedCount(): int;
    public function getUnCompressedCount(): int;
    public function getMediasId(): array;
    public function getUnCompressed(): array;
}
