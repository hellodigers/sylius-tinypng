<?php

namespace Dige\TinypngPlugin\Service\Compress;

interface CompressImageInterface
{
    public function __invoke(string $path): void;
}
