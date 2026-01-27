<?php

namespace App\Infrastructure\Entrypoints\Http\Rest;

use App\Application\Command\ReactToUserCommand;
use App\Application\DTO\ReactToUserDTO;
use App\Infrastructure\Cache\UserReactionsStoringCache;
use App\Infrastructure\Entrypoints\Http\BaseController;
use Exception;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag(name: 'User Reactions')]
#[Route('/reactions', name: 'api_reactions_')]
class UserReactionsController extends BaseController
{
    /**
     * @throws ExceptionInterface|InvalidArgumentException
     */
    #[Route('', name: 'create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload]
        ReactToUserDTO $dto,
        LoggerInterface $logger,
        MessageBusInterface $messageBus,
        UserReactionsStoringCache $userReactionsStoringCache,
        RateLimiterFactory $userReactionsCommandLimiter,
    ): JsonResponse {
        try {
            $userId = $this->getUser()->getId();
            $userReactionsStoringCache->append($userId, $dto);

            $limiter = $userReactionsCommandLimiter->create('user_reactions_command_limit');
            if (false === $limiter->consume()->isAccepted()) {
                return $this->json(null, Response::HTTP_NO_CONTENT, ['X-RateLimit' => 'No dispatch']);
            }

            $messageBus->dispatch(
                new ReactToUserCommand($userId),
                [new DelayStamp(10*1000)]
            );

            return $this->json(
                null, Response::HTTP_NO_CONTENT, ['X-RateLimit' => 'Dispatched']
            );
        } catch (Exception $e) {
            $logger->error($e->getMessage());
            return $this->json(
                ['error' => 'Failed to save user reactions'], Response::HTTP_BAD_REQUEST
            );
        }
    }
}
