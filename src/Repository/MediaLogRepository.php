<?php

declare(strict_types=1);

namespace Dige\TinypngPlugin\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class MediaLogRepository extends EntityRepository implements MediaLogRepositoryInterface
{
    public function getCompressedCount(): int
    {
        return (int)$this->createQueryBuilder('ml')
            ->select('COUNT(1) as count')
            ->andWhere('ml.compressedAt IS NOT NULL')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getUnCompressedCount(): int
    {
        return (int)$this->createQueryBuilder('ml')
            ->select('COUNT(1)')
            ->andWhere('ml.compressedAt IS NULL')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getMediasId(): array
    {
        return array_column($this->createQueryBuilder('m')
            ->select('m.mediaId')
            ->getQuery()
            ->getArrayResult(),'mediaId');
    }

    public function getUnCompressed(): array
    {
        return $this->createQueryBuilder('ml')
            ->andWhere('ml.compressedAt IS NULL')
            ->getQuery()
            ->getResult();
    }
}
