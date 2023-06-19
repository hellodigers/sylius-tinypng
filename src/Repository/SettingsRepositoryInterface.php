<?php

namespace Dige\TinypngPlugin\Repository;

use Dige\TinypngPlugin\Entity\Settings;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface SettingsRepositoryInterface extends RepositoryInterface
{
    public function findLast(): ?Settings;
}
