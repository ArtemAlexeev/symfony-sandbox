<?php

namespace App\Shared\Infrastructure\EventSubscriber;

use App\Domain\Exceptions\DomainException;
use ReflectionClass;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            // We set a high priority to catch it before Symfony's default handlers
            KernelEvents::EXCEPTION => ['onKernelException', 10],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof UnprocessableEntityHttpException) {
            $response = new JsonResponse([
                'error' => [
                    'message' => $exception->getMessage(),
                    'type' => (new ReflectionClass($exception))->getShortName(),
                ]
            ], $exception->getCode() ?: 400);

            $event->setResponse($response);
        }

        // We only want to handle our custom Domain Exceptions
        if (!$exception instanceof DomainException) {
            return;
        }

        // Use the code from the exception as the HTTP status code (default to 400)
        $statusCode = $exception->getCode() ?: 400;

        $response = new JsonResponse([
            'error' => [
                'message' => $exception->getMessage(),
                'type' => (new ReflectionClass($exception))->getShortName(),
            ]
        ], $statusCode);

        $event->setResponse($response);
    }
}
