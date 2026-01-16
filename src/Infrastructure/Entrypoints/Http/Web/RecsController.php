<?php

namespace App\Infrastructure\Entrypoints\Http\Web;

use App\Infrastructure\Entrypoints\Http\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/recs', name: 'web_recommendations_')]
class RecsController extends BaseController
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('recommendations.html.twig', [
            'profile' => $this->getUser()->getProfile(),
        ]);
    }
}
