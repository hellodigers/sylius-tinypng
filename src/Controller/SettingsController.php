<?php

declare(strict_types=1);

namespace Dige\TinypngPlugin\Controller;

use Dige\TinypngPlugin\Entity\Log;
use Dige\TinypngPlugin\Entity\Settings;
use Dige\TinypngPlugin\Form\SettingsType;
use Dige\TinypngPlugin\Message\CompressImages;
use Dige\TinypngPlugin\Message\CreateMediaLogs;
use Dige\TinypngPlugin\Repository\LogRepositoryInterface;
use Dige\TinypngPlugin\Repository\MediaLogRepositoryInterface;
use Dige\TinypngPlugin\Repository\SettingsRepositoryInterface;
use Dige\TinypngPlugin\Service\Api\ApiServiceInterface;
use Dige\TinypngPlugin\Service\Cache\CacheServiceInterface;
use Dige\TinypngPlugin\Service\Consts\CacheNames;
use Dige\TinypngPlugin\Service\Settings\CreateLogInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class SettingsController extends AbstractController
{
    private MessageBusInterface $defaultBus;
    private CreateLogInterface $createLog;
    private SettingsRepositoryInterface $repository;
    private LogRepositoryInterface $logRepository;
    private TranslatorInterface $translator;
    private ApiServiceInterface $apiService;
    private MediaLogRepositoryInterface $mediaLogRepository;
    private CacheServiceInterface $cacheService;

    public function __construct(
        TranslatorInterface $translator,
        MessageBusInterface $defaultBus,
        CreateLogInterface $createLog,
        SettingsRepositoryInterface $repository,
        LogRepositoryInterface $logRepository,
        ApiServiceInterface $apiService,
        MediaLogRepositoryInterface $mediaLogRepository,
        CacheServiceInterface $cacheService)
    {
        $this->defaultBus = $defaultBus;
        $this->createLog = $createLog;
        $this->repository = $repository;
        $this->logRepository = $logRepository;
        $this->translator = $translator;
        $this->apiService = $apiService;
        $this->mediaLogRepository = $mediaLogRepository;
        $this->cacheService = $cacheService;
    }

    public function settingsAction(Request $request): Response
    {
        $settings = $this->repository->findLast();
        $oldApiKey = $settings->getApiKey();
        $form = $this->createForm(SettingsType::class, $settings);
        $logs = $this->logRepository->findBy([], ['createdAt' => 'DESC'],10);
        $itsStartedNew = $this->hasUnfinishedLog($logs);

        if ($request->isMethod(Request::METHOD_POST)) {
            if ($request->get('is_compress_all', false)) {
                $itsStartedNew = true;
                $this->defaultBus->dispatch(new Envelope(new CompressImages()));
                $logs[] = ($this->createLog)($this->getUser()->getUserIdentifier());
                $this->addFlash('success', $this->translator->trans('tinypng.ui.settings.compress_registered'));
            } else {
                $form->handleRequest($request);
                if ($form->isValid()) {
                    /** @var Settings $settings */
                    $settings = $form->getData();
                    if($oldApiKey !== $settings->getApiKey()) {
                        $settings->setApiKeyLimitExceeded(false);
                    }
                    $this->repository->add($settings);
                    $this->addFlash('success', $this->translator->trans('tinypng.ui.settings.setting_saved'));
                }
            }
        }

        return $this->render('@DigeSyliusTinypngPlugin/Settings/index.html.twig',[
            'form' => $form->createView(),
            'logs' => $logs,
            'settingsAvailable' => !!$settings,
            'itsStartedNew' => $itsStartedNew,
            'apiCount' => $this->apiService->getCount(),
            'compressedCount' => $this->mediaLogRepository->getCompressedCount(),
            'unCompressedCount' => $this->mediaLogRepository->getUnCompressedCount(),
            'loadImagesInProgress' => (int)$this->cacheService->get(CacheNames::CACHE_KEY_REGISTERED_MEDIA_LOGS),
            'actualToCompressCount' => $this->cacheService->get(CacheNames::CACHE_KEY_COMPRESSED_COUNT),
            'actualUnCompressedCount' => $this->cacheService->get(CacheNames::CACHE_KEY_UNCOMPRESSED_COUNT),
            'actualOverallFileCount' => $this->cacheService->get(CacheNames::CACHE_KEY_OVERALL_FILE_COUNT),
            'actualMediaLogCount' => $this->cacheService->get(CacheNames::CACHE_KEY_CREATED_MEDIA_LOG_FOR_FILE_COUNT),
            'isApiExceeded' => $settings->isApiKeyLimitExceeded()
        ]);
    }

    public function loadMediaAction(Request $request): Response
    {
        $itsStarted = $this->cacheService->get(CacheNames::CACHE_KEY_REGISTERED_MEDIA_LOGS);

        if($itsStarted !== null && $itsStarted) {
            $this->addFlash('warning', $this->translator->trans('tinypng.ui.settings.media_logs_registered_in_progress'));
        } else {
            $this->defaultBus->dispatch(new Envelope(new CreateMediaLogs()));
            $this->addFlash('success', $this->translator->trans('tinypng.ui.settings.media_logs_registered'));
            $this->cacheService->set(CacheNames::CACHE_KEY_REGISTERED_MEDIA_LOGS, 1);
        }

        return $this->redirectToRoute('dige_sylius_tinypng_plugin_settings');
    }

    private function hasUnfinishedLog(iterable $logs): bool
    {
        /** @var Log $log */
        foreach ($logs as $log) {
            if(!$log->isFinished()) {
                return true;
            }
        }

        return false;
    }

    public function getInProgressCountsAction(): Response
    {
        $logs = $this->logRepository->findBy([], ['createdAt' => 'DESC'],10);
        $overallCompressedCount = $this->mediaLogRepository->getCompressedCount();
        $unCompressedCount = $this->mediaLogRepository->getUnCompressedCount();

        return $this->json([
            'loadImagesInProgress' => (int)$this->cacheService->get(CacheNames::CACHE_KEY_REGISTERED_MEDIA_LOGS),
            'actualCompressCount' => $overallCompressedCount + (int)$this->cacheService->get(CacheNames::CACHE_KEY_COMPRESSED_COUNT) ?? 0,
            'actualUnCompressedCount' => $unCompressedCount + $overallCompressedCount,
            'actualOverallFileCount' => $this->cacheService->get(CacheNames::CACHE_KEY_OVERALL_FILE_COUNT) ?? 0,
            'actualMediaLogCount' => $this->cacheService->get(CacheNames::CACHE_KEY_CREATED_MEDIA_LOG_FOR_FILE_COUNT) ?? 0,
            'compressOn' => (int)$this->hasUnfinishedLog($logs)
        ]);
    }
}
