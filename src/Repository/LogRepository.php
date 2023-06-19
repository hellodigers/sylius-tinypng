<?php

declare(strict_types=1);

namespace Dige\TinypngPlugin\Repository;

use Dige\TinypngPlugin\Entity\Log;
use Doctrine\ORM\NoResultException;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class LogRepository extends EntityRepository implements LogRepositoryInterface
{
    public function findUnfinished(): ?Log
    {
        try {
            return $this->createQueryBuilder('l')
                ->andWhere('l.finishedAt IS NULL')
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }
}
