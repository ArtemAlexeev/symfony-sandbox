<?php

namespace App\Domain\Entity;

use App\Domain\Enum\User\ReactionType;
use App\Infrastructure\Persistence\Doctrine\UserReactionRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserReactionRepository::class)]
#[ORM\UniqueConstraint(
    name: 'user_target_unique',
    columns: ['user_id', 'target_user_id']
)]
#[ORM\HasLifecycleCallbacks]
class UserReaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userReactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userRetrievedReactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $targetUser = null;

    #[ORM\Column(type: 'string', length: 100, enumType: ReactionType::class)]
    private ReactionType $type;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getTargetUser(): ?User
    {
        return $this->targetUser;
    }

    public function setTargetUser(?User $targetUser): static
    {
        $this->targetUser = $targetUser;
        return $this;
    }

    public function getType(): ReactionType
    {
        return $this->type;
    }

    public function setType(ReactionType $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist] // <--- This runs automatically on "persist"
    public function setCreatedAtValue(): void
    {
        if ($this->createdAt === null) {
            $this->createdAt = new DateTimeImmutable();
        }
    }
}
