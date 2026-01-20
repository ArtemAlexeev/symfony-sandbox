<?php

namespace App\Infrastructure\Entrypoints\Http\Rest;

use App\Domain\Enum\User\ReactionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reaction-types', name: 'api_reaction_types_')]
class UserReactionsTypeController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json([
            'data' => ReactionType::cases()
        ]);
    }
}
