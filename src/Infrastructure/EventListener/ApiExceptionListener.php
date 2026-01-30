<?php

namespace App\Infrastructure\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Exception\ValidationFailedException;

#[AsEventListener(event: KernelEvents::EXCEPTION)]
final readonly class ApiExceptionListener
{
    public function __construct(
        private LoggerInterface $logger,
        private bool $isDebug,
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();

        // 1. Check if this is an API request (optional)
        // You can check the path, or the 'Accept' header.
        if (!str_starts_with($request->getPathInfo(), '/api') &&
            !str_starts_with($request->getPathInfo(), '/user')) {
            return;
        }

        // 2. Determine Status Code
        if ($exception instanceof HttpExceptionInterface) {
            // Handles 404 Not Found, 403 Forbidden, etc. automatically
            $statusCode = $exception->getStatusCode();
        } elseif ($exception instanceof \InvalidArgumentException) {
            // Map standard PHP exceptions to 400 if you want
            $statusCode = Response::HTTP_BAD_REQUEST;
        } elseif ($exception instanceof \DomainException) {
            // Map your custom DDD Domain Exceptions to 400 or 422
            $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        } else {
            // Default to 500 for everything else
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        // 3. Prepare the Response Data
        $data = [
            'status' => $statusCode,
            'error' => $statusCode === 500 ? 'Internal Server Error' : $exception->getMessage(),
        ];

        if ($exception instanceof UnprocessableEntityHttpException) {
            $previous = $exception->getPrevious();
            if ($previous instanceof ValidationFailedException) {
                $violations = [];
                foreach ($previous->getViolations() as $violation) {
                    $violations[$violation->getPropertyPath()] = $violation->getMessage();
                }

                // Override the generic message with specific fields
                $data['violations'] = $violations;
            }
        }

        // Add debug info if we are in dev mode (and it's not a production 500 error)
        if ($this->isDebug) {
            $data['trace'] = $exception->getTraceAsString();
            $data['class'] = get_class($exception);
        }

        // 4. Log 500 errors (Critical)
        if ($statusCode >= 500) {
            $this->logger->critical($exception->getMessage(), [
                'trace' => $exception->getTraceAsString(),
            ]);
        }

        // 5. Send the Response
        $event->setResponse(new JsonResponse($data, $statusCode));
    }
}
