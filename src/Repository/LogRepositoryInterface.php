<?php

namespace Dige\TinypngPlugin\Repository;

use Dige\TinypngPlugin\Entity\Log;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface LogRepositoryInterface extends RepositoryInterface
{
    public function findUnfinished(): ?Log;
}
