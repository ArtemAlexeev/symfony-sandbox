<?php

namespace App\Infrastructure\Entrypoints\Http\Rest;

use App\Infrastructure\Entrypoints\Http\BaseController;
use OpenApi\Attributes as OA;
use App\Infrastructure\Persistence\Doctrine\ProfileRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag(name: 'User Recommendations')]
#[Route('/recs', name: 'api_recs_')]
class RecsController extends BaseController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(ProfileRepository $repo): JsonResponse
    {
        return $this->json([
            'data' => $repo->findRecs($this->getUser())
        ]);
    }
}
