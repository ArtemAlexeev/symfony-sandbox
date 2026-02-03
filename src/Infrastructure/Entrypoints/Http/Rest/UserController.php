<?php

namespace App\Infrastructure\Entrypoints\Http\Rest;

use App\Application\DTO\Response\UserDTO;
use App\Application\DTO\TestDTO;
use App\Domain\Entity\User;
use App\Infrastructure\Entrypoints\Http\BaseController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[OA\Tag(name: 'User')]
#[Route('/user', name: 'api_user_')]
class UserController extends BaseController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(#[CurrentUser] User $user): JsonResponse
    {
        return $this->json(['data' => UserDTO::fromEntity($user)]);
    }

    #[Route('/test', name: 'test', methods: ['POST'])]
    public function justTest(#[MapRequestPayload]TestDTO $dto): JsonResponse
    {
        return $this->json(['data' => $dto, 'dto' => $dto->status]);
    }
}
