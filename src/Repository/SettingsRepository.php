<?php

declare(strict_types=1);

namespace Dige\TinypngPlugin\Repository;

use Dige\TinypngPlugin\Entity\Settings;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class SettingsRepository extends EntityRepository implements SettingsRepositoryInterface
{
    public function findLast(): ?Settings
    {
        try {
            return $this->createQueryBuilder('s')
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }
}
