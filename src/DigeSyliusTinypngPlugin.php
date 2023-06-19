<?php

declare(strict_types=1);

namespace Dige\TinypngPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class DigeSyliusTinypngPlugin extends Bundle
{
    use SyliusPluginTrait;
}
