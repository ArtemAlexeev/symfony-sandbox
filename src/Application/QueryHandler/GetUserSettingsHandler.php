<?php

namespace App\Application\QueryHandler;

use App\Application\Query\GetUserSettingsQuery;
use App\Domain\Entity\UserSettings;
use App\Infrastructure\Persistence\Doctrine\UserSettingsRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetUserSettingsHandler
{
    public function __construct(
        private UserSettingsRepository $repository
    ) {
    }

    public function __invoke(GetUserSettingsQuery $query): ?UserSettings
    {
        return $this->repository->findOneByUser($query->user);
    }
}
