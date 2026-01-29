<?php

namespace App\Domain\Entity;
use App\Domain\Enum\User\Language;
use Doctrine\ORM\Mapping as ORM;
use App\Infrastructure\Persistence\Doctrine\UserSettingsRepository;

#[ORM\Entity(repositoryClass: UserSettingsRepository::class)]
class UserSettings
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    #[ORM\Column()]
    private bool $pushEnabled = false;

    #[ORM\Column()]
    private bool $emailEnabled = false;

    #[ORM\Column(length: 10, nullable: false, enumType: Language::class)]
    private Language $language = Language::English;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(unique: true, nullable: false)]
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPushEnabled(): bool
    {
        return $this->pushEnabled;
    }

    public function setPushEnabled(bool $pushEnabled): self
    {
        $this->pushEnabled = $pushEnabled;
        return $this;
    }

    public function getEmailEnabled(): bool
    {
        return $this->emailEnabled;
    }

    public function setEmailEnabled(bool $emailEnabled): self
    {
        $this->emailEnabled = $emailEnabled;
        return $this;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function setLanguage(Language $language): self
    {
        $this->language = $language;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
