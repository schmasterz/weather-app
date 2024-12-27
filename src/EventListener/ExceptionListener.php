<?php
declare(strict_types=1);

namespace App\EventListener;

use App\Exception\ApiKeyUnauthorizedException;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ApiKeyUnauthorizedException) {
            $response = new JsonResponse([
                'error' => $exception->getMessage()
            ], JsonResponse::HTTP_UNAUTHORIZED);

            $event->setResponse($response);
        }
    }
}
