<?php

namespace Dige\TinypngPlugin\Service\Cache;

interface CacheServiceInterface
{
    public function get(string $cacheName);
    public function set(string $cacheName, $value): void;
}
