<?php

namespace App\Infrastructure\Entrypoints\Http\Rest;

use App\Domain\Enum\User\ReactionType;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag(name: 'User Reaction Types')]
#[Route('/reaction-types', name: 'api_reaction_types_')]
class UserReactionsTypeController extends AbstractController
{
    #[OA\Get(description: 'Get all available reaction types', summary: "All types")]
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json([
            'data' => ReactionType::cases()
        ]);
    }
}
