<?php

declare(strict_types=1);

namespace Dige\TinypngPlugin\Service\Cache;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\CacheItem;

class CacheService implements CacheServiceInterface
{
    private FilesystemAdapter $cacheAdapter;

    public function __construct()
    {
        $this->cacheAdapter = new FilesystemAdapter();
    }

    public function get(string $cacheName)
    {
        return $this->cacheAdapter->getItem($cacheName)->get();
    }

    public function set(string $cacheName, $value): void
    {
        /** @var CacheItem $item */
        $item = $this->cacheAdapter->getItem($cacheName);
        $item->set($value);
        $this->cacheAdapter->save($item);
    }
}
