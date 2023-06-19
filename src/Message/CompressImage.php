<?php

declare(strict_types=1);

namespace Dige\TinypngPlugin\Message;

class CompressImage
{
    public int $entityId;
    public string $entityImageClass;

    public function __construct(int $entityId, string $entityImageClass)
    {
        $this->entityId = $entityId;
        $this->entityImageClass = $entityImageClass;
    }
}
