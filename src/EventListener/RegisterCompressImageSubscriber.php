<?php

declare(strict_types=1);

namespace Dige\TinypngPlugin\EventListener;

use Dige\TinypngPlugin\Message\CompressImage;
use Dige\TinypngPlugin\Repository\SettingsRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Sylius\Component\Core\Model\ImageInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class RegisterCompressImageSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $defaultBus;
    private SettingsRepositoryInterface $settingsRepository;

    public function __construct(MessageBusInterface $defaultBus, SettingsRepositoryInterface $settingsRepository)
    {
        $this->defaultBus = $defaultBus;
        $this->settingsRepository = $settingsRepository;
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->register($args);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->register($args);
    }

    private function register(LifecycleEventArgs $event): void
    {
        /** @var ImageInterface $image */
        $image = $event->getObject();
        $settings = $this->settingsRepository->findLast();

        if ($image instanceof ImageInterface && $settings && $settings->isEnabled()) {
            $this->defaultBus->dispatch(new Envelope(new CompressImage($image->getId(), get_class($image))));
        }
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postUpdate,
        ];
    }
}
