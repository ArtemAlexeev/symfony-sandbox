<?php

namespace App\Infrastructure\EventListener;

use App\Domain\Exceptions\DomainException;
use InvalidArgumentException;
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

        if (!str_starts_with($request->getPathInfo(), '/api')) {
            return;
        }

        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
        } elseif ($exception instanceof InvalidArgumentException) {
            $statusCode = Response::HTTP_BAD_REQUEST;
        } elseif ($exception instanceof DomainException) {
            $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        } else {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

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

                $data['violations'] = $violations;
            }
        }

        if ($this->isDebug) {
            $data['trace'] = $exception->getTraceAsString();
            $data['class'] = get_class($exception);
        }

        if ($statusCode >= 500) {
            $this->logger->critical($exception->getMessage(), [
                'trace' => $exception->getTraceAsString(),
            ]);
        }

        $event->setResponse(new JsonResponse($data, $statusCode));
    }
}
