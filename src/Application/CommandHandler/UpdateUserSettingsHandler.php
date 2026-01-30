<?php

namespace App\Application\CommandHandler;

use App\Application\Command\UpdateUserSettingsCommand;
use App\Domain\Entity\UserSettings;
use App\Domain\Enum\User\Language;
use App\Infrastructure\Persistence\Doctrine\UserSettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UpdateUserSettingsHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserSettingsRepository $userSettingsRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(UpdateUserSettingsCommand $command): UserSettings
    {
        $settings = $this->userSettingsRepository->findOneByUser($command->user);
        if (!$settings) {
            $settings = new UserSettings($command->user);
            $this->entityManager->persist($settings);
            $this->entityManager->flush();
        }
        $dto = $command->dto;

        if (isset($dto->emailEnabled)) $settings->setEmailEnabled($dto->emailEnabled);
        if (isset($dto->pushEnabled)) $settings->setPushEnabled($dto->pushEnabled);
        if (isset($dto->language)) $settings->setLanguage(Language::from($dto->language));

        $this->entityManager->flush();

        return $settings;
    }
}
