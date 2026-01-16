<?php

namespace App\Application\CommandHandler;

use App\Application\Command\UpdateProfileCommand;
use App\Domain\Enum\User\Gender;
use App\Domain\Enum\User\Status;
use App\Domain\Exceptions\UserMustBeAboveMinYearsOldException;
use Doctrine\ORM\EntityManagerInterface;

class UpdateProfileHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws UserMustBeAboveMinYearsOldException
     */
    public function handle(UpdateProfileCommand $command): void
    {
        $command->profile->putDetails(
            $command->dto->firstName,
            $command->dto->lastName,
            $command->dto->age,
            Gender::from($command->dto->gender),
            Status::from($command->dto->status),
            $command->dto->description,
            $command->dto->avatar,
        );

        $this->entityManager->flush();
    }
}
