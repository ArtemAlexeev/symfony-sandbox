<?php

namespace App\Infrastructure\Entrypoints\Http\Rest;

use App\Application\Command\ReactToUserCommand;
use App\Application\CommandHandler\ReactToUserHandler;
use App\Application\DTO\ReactToUserDTO;
use App\Infrastructure\Entrypoints\Http\BaseController;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reactions', name: 'api_reactions_')]
class UserReactionsController extends BaseController
{
    #[Route('', name: 'create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload]
        ReactToUserDTO $dto,
        ReactToUserHandler $handler,
        LoggerInterface $logger
    ): JsonResponse {
        try {
            $command = new ReactToUserCommand($this->getUser(), $dto);
            $handler->handle($command);

            return $this->json('', Response::HTTP_CREATED);
        } catch (Exception $e) {
            $logger->error($e->getMessage());
            return $this->json(['error' => 'Failed to save user reactions'], Response::HTTP_BAD_REQUEST);
        }
    }
}
