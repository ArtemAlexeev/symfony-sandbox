<?php

namespace App\Application\CommandHandler;

use App\Application\Command\UpdateProfileCommand;
use App\Domain\Enum\User\Gender;
use App\Domain\Enum\User\Status;
use App\Domain\Exceptions\UserMustBeAboveMinYearsOldException;
use App\Domain\ValueObject\Age;
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
        $gender = $command->dto->gender
            ? Gender::from($command->dto->gender)
            : null;
        $status = $command->dto->status
            ? Status::from($command->dto->status)
            : null;

        $command->profile->putDetails(
            $command->dto->firstName,
            $command->dto->lastName,
            new Age($command->dto->age),
            $gender,
            $status,
            $command->dto->description,
            $command->dto->avatar,
        );

        $this->entityManager->flush();
    }
}
