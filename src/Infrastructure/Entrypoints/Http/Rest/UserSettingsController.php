<?php

namespace App\Infrastructure\Entrypoints\Http\Rest;

use App\Application\Command\UpdateUserSettingsCommand;
use App\Application\DTO\Response\UserSettingsDTO;
use App\Application\DTO\UpdateUserSettingsDTO;
use App\Application\Query\GetUserSettingsQuery;
use App\Domain\Entity\User;
use App\Domain\Entity\UserSettings;
use App\Infrastructure\Entrypoints\Http\BaseController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[OA\Tag(name: 'User Settings')]
#[Route('/user/settings', name: 'api_user_settings_')]
class UserSettingsController extends BaseController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(#[CurrentUser] User $user): JsonResponse
    {
        $envelope = $this->messageBus->dispatch(new GetUserSettingsQuery($user));

        /** @var UserSettings $userSettings */
        $userSettings = $envelope->last(HandledStamp::class)->getResult();

        return $this->json(['data' => UserSettingsDTO::fromEntity($userSettings)]);
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('', name: 'update', methods: ['PATCH'])]
    public function update(
        #[MapRequestPayload] UpdateUserSettingsDTO $dto,
        #[CurrentUser] User $user
    ): JsonResponse {
        $command = new UpdateUserSettingsCommand($user, $dto);
        $envelope = $this->messageBus->dispatch($command);

        /** @var UserSettings $userSettings */
        $userSettings = $envelope->last(HandledStamp::class)->getResult();

        return $this->json(['data' => UserSettingsDTO::fromEntity($userSettings)]);
    }
}
