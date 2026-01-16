<?php

namespace App\Domain\Entity;

use App\Domain\Enum\User\Gender;
use App\Domain\Enum\User\Status;
use App\Domain\Exceptions\UserMustBeAboveMinYearsOldException;
use App\Domain\ValueObject\Age;
use App\Infrastructure\Persistence\Doctrine\ProfileRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(nullable: true)]
    private ?int $age = null;

    #[ORM\Column(length: 10, nullable: true, enumType: Gender::class)]
    private ?Gender $gender = null;

    #[ORM\Column(length: 100, nullable: true, enumType: Status::class)]
    private ?Status $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    #[Ignore]
    #[ORM\OneToOne(inversedBy: 'profile', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function getGender(): ?Gender
    {
        return $this->gender; //return enum and not string
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function getStatusLabel(): ?string
    {
        return $this->status->label();
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getUserId(): int
    {
        return $this->getUser()->getId();
    }

    /**
     * @throws UserMustBeAboveMinYearsOldException
     */
    public function putDetails(
        ?string $firstName,
        ?string $lastName,
        ?int $age,
        ?Gender $gender,
        ?Status $status,
        ?string $description,
        ?string $avatar,
    ): void {
        if ($age) {
            $age = new Age($age);
        }

        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->age = $age->getValue();
        $this->gender = $gender;
        $this->status = $status;
        $this->description = $description;
        $this->avatar = $avatar;
    }
}
