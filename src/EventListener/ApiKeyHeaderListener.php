<?php
declare(strict_types=1);

namespace App\EventListener;

use App\Exception\ApiKeyUnauthorizedException;
use App\Service\ApiKeyService;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class ApiKeyHeaderListener
{
    private ApiKeyService $apiKeyService;

    public function __construct(ApiKeyService $apiKeyService)
    {
        $this->apiKeyService = $apiKeyService;
    }
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (str_starts_with($request->getPathInfo(), '/api/')) {
            $apiKey = $request->headers->get('apiKey');
            if (!$apiKey) {
                throw new ApiKeyUnauthorizedException(
                    'No API key provided in headers'
                );
            }
            if (!$this->apiKeyService->verifyApiKey($apiKey)) {
                throw new ApiKeyUnauthorizedException(
                    'Invalid API key provided in headers'
                );
            }
        }
    }
}
