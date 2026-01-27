<?php

namespace App\Application\CommandHandler;

use App\Application\Command\ReactToUserCommand;
use App\Domain\Entity\UserReaction;
use App\Domain\Enum\User\ReactionType;
use App\Infrastructure\Cache\UserReactionsStoringCache;
use App\Infrastructure\Persistence\Doctrine\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ReactToUserHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private LoggerInterface $logger,
        private UserReactionsStoringCache $reactionsStoringCache
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(ReactToUserCommand $command): void
    {
        try {
            $this->process($command);
        } catch (Exception $e) {
            $this->logger->error("Error processing ReactToUserCommand", ['exception' => $e]);
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    private function process(ReactToUserCommand $command): void
    {
        $reactions = $this->reactionsStoringCache->get($command->userId);

        if (empty($reactions) ) {
            $this->logger->warning('ReactToUserCommand - No reactions found in cache', ['userId' => $command->userId]);
            return;
        }

        $uniqueReactions = [];
        foreach ($reactions as $reaction) {
            $uniqueReactions[$reaction->targetUserId] = ReactionType::from($reaction->type);
        }

        $user = $this->userRepository->find($command->userId);

        if (!$user) {
            throw new Exception("ReactToUserCommand - User not found " . $command->userId);
        }

        $this->reactionsStoringCache->delete($command->userId);

        foreach ($uniqueReactions as $targetUserId => $reactionType) {
            $targetUser = $this->userRepository->find($targetUserId);
            if (!$targetUser) {
                $this->logger->warning(
                    "ReactToUserCommand - Target user not found", ['targetUserId' => $targetUserId]
                );
                continue;
            }

            if ($user->getId() === $targetUser->getId()) {
                $this->logger->warning(
                    "ReactToUserCommand - Target user equals to user", ['targetUserId' => $targetUserId]
                );
                continue;
            }

            $reaction = new UserReaction();
            $reaction->setUser($user);
            $reaction->setTargetUser($targetUser);
            $reaction->setType($reactionType);
            $this->entityManager->persist($reaction);
        }

        $this->entityManager->flush();
    }
}
