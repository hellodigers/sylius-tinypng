<?php

declare(strict_types=1);

namespace Dige\TinypngPlugin\EventListener;

use Dige\TinypngPlugin\Service\Consts\CacheNames;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;
use Symfony\Component\Messenger\Event\WorkerMessageHandledEvent;

class ClearCachedInProgressLoadMediaLogsSubscriber implements EventSubscriberInterface
{
    public function clear(): void
    {
        $filesystemAdapter = new FilesystemAdapter();

        $filesystemAdapter->clear(CacheNames::CACHE_KEY_REGISTERED_MEDIA_LOGS);
    }

    public static function getSubscribedEvents()
    {
        return [
            WorkerMessageHandledEvent::class => ['clear'],
            WorkerMessageFailedEvent::class => ['clear']
        ];
    }
}
