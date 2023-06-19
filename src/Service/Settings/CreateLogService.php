<?php

declare(strict_types=1);

namespace Dige\TinypngPlugin\Service\Settings;

use Dige\TinypngPlugin\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;

class CreateLogService implements CreateLogInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(string $username): Log
    {
        $log = new Log();

        $log->setUsername($username);
        $this->entityManager->persist($log);
        $this->entityManager->flush();
        
        return $log;
    }
}
