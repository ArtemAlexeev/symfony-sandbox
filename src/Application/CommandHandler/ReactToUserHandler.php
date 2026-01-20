<?php

namespace App\Application\CommandHandler;

use App\Application\Command\ReactToUserCommand;
use App\Domain\Entity\UserReaction;
use App\Domain\Enum\User\ReactionType;
use App\Domain\Exceptions\SelfReactionException;
use App\Domain\Exceptions\UserNotFoundException;
use App\Infrastructure\Persistence\Doctrine\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class ReactToUserHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository
    ) {
    }

    /**
     * @throws UserNotFoundException
     * @throws SelfReactionException
     */
    public function handle(ReactToUserCommand $command): void
    {
        $targetUser = $this->userRepository->find($command->dto->targetUserId);

        if (!$targetUser) {
            throw new UserNotFoundException($command->dto->targetUserId);
        }

        if ($command->user->getId() === $targetUser->getId()) {
            throw new SelfReactionException();
        }

        $reaction = new UserReaction();
        $reaction->setUser($command->user);
        $reaction->setTargetUser($targetUser);
        $reaction->setType(ReactionType::from($command->dto->type));

        $this->entityManager->persist($reaction);
        $this->entityManager->flush();
    }
}
