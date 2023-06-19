<?php

namespace Dige\TinypngPlugin\Service\Settings;

use Dige\TinypngPlugin\Entity\Log;

interface CreateLogInterface
{
    public function __invoke(string $username): Log;
}
