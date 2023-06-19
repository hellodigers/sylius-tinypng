<?php

declare(strict_types=1);

namespace Dige\TinypngPlugin\Message\Handler;

use Dige\TinypngPlugin\Message\CreateMediaLogs;
use Dige\TinypngPlugin\Service\Compress\Log\CompressMediaLogServiceInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateMediaLogsHandler implements MessageHandlerInterface
{
    private CompressMediaLogServiceInterface $compressMediaLogService;

    public function __construct(CompressMediaLogServiceInterface $compressMediaLogService)
    {
        $this->compressMediaLogService = $compressMediaLogService;
    }

    public function __invoke(CreateMediaLogs $createMediaLogs)
    {
        $this->compressMediaLogService->createLogs();
    }
}
