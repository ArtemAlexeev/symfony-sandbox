<?php

namespace App\Infrastructure\Entrypoints\Http\Rest;

use App\Application\Command\UpdateProfileCommand;
use App\Application\CommandHandler\UpdateProfileHandler;
use App\Application\DTO\ProfileDTO;
use App\Application\DTO\Response\UserDTO;
use App\Infrastructure\Entrypoints\Http\BaseController;
use Nelmio\ApiDocBundle\Attribute\Model;
use Psr\Log\LoggerInterface;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[OA\Tag(name: 'User Profile')]
#[Route('/profile', name: 'api_profile_')]
class ProfileController extends BaseController
{
    #[OA\Response(
        response: 200,
        description: 'Returns user profile',
        content: new OA\JsonContent(ref: new Model(type: UserDTO::class))
    )]
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(LoggerInterface $logger): JsonResponse
    {
        try {
            return $this->json([
                'data' =>  [
                    'profile' => $this->getUser()->getProfile(),
                    'user' => UserDTO::fromEntity(
                        $this->getUser()
                    ),
                ]
            ]);
        } catch (Throwable $e) {
            $logger->error($e->getMessage());
            return $this->json([
                'msg'   => 'Failed to get profile',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('', name: 'update', methods: ['PUT'])]
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
                'msg'   => 'Failed to update profile',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
