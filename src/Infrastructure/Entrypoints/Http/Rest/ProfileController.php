<?php

namespace App\Infrastructure\Entrypoints\Http\Rest;

use App\Application\Command\UpdateProfileCommand;
use App\Application\CommandHandler\UpdateProfileHandler;
use App\Application\DTO\ProfileDTO;
use App\Infrastructure\Entrypoints\Http\BaseController;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[Route('/profile', name: 'api_profile_')]
class ProfileController extends BaseController
{
    #[Route('', name: 'index', methods: ['PUT'])]
    public function update(
        UpdateProfileHandler $handler,
        #[MapRequestPayload] ProfileDTO $dto,
        LoggerInterface $logger
    ): JsonResponse {
        try {
            $command = new UpdateProfileCommand($this->getUser()->getProfile(), $dto);
            $handler->handle($command);

            return $this->json([
                'data' => $this->getUser()->getProfile(),
            ]);
        } catch (Throwable $e) {
            $logger->error($e->getMessage());
            return $this->json([
                'error' => 'Failed to update profile',
                'details' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
