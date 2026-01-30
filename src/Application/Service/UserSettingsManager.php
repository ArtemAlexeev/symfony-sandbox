<?php

namespace App\Application\Service;

use App\Domain\Entity\User;
use App\Domain\Entity\UserSettings;
use App\Infrastructure\Persistence\Doctrine\UserSettingsRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class UserSettingsManager
{
    public function __construct(
        private UserSettingsRepository $repository,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function getOrCreateSettings(User $user): UserSettings
    {
        $settings = $this->repository->findOneByUser($user);

        if (!$settings) {
            $settings = new UserSettings($user);
            $this->entityManager->persist($settings);
            $this->entityManager->flush();
        }

        return $settings;
    }
}
