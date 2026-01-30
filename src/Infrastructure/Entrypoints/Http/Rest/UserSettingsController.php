<?php

namespace App\Infrastructure\Entrypoints\Http\Rest;

use App\Application\Command\UpdateUserSettingsCommand;
use App\Application\CommandHandler\UpdateUserSettingsHandler;
use App\Application\DTO\Response\UserSettingsDTO;
use App\Application\DTO\UpdateUserSettingsDTO;
use App\Application\Service\UserSettingsManager;
use App\Infrastructure\Entrypoints\Http\BaseController;
use Exception;
use OpenApi\Attributes as OA;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag(name: 'User Settings')]
#[Route('/user/settings', name: 'api_user_settings_')]
class UserSettingsController extends BaseController
{
    public function __construct(
        private LoggerInterface $businessLogger,
    ) {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(UserSettingsManager $userSettingsManager): JsonResponse
    {
        try {
            return $this->json([
                'data' => UserSettingsDTO::fromEntity(
                    $userSettingsManager->getOrCreateSettings($this->getUser())
                ),
            ]);
        } catch (Exception $e) {
            $this->businessLogger->error('Error fetching user settings', [
                'msg' => $e->getMessage(),
            ]);

            return $this->json([
                'error' => 'An error occurred while fetching user settings.',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('', name: 'patch', methods: ['PATCH'])]
    public function patchSettings(
        #[MapRequestPayload] UpdateUserSettingsDTO $dto,
        UpdateUserSettingsHandler $handler
    ): JsonResponse {
        try {
            $command = new UpdateUserSettingsCommand($this->getUser(), $dto);

            return $this->json([
                'data' => UserSettingsDTO::fromEntity($handler->handle($command)),
            ]);
        } catch (Exception $e) {
            $this->businessLogger->error('Error updating user settings', [
                'msg' => $e->getMessage(),
            ]);

            return $this->json([
                'error' => 'An error occurred while updating user settings.',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
