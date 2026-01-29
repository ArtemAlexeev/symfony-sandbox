<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Entity\Profile;
use App\Domain\Entity\User;
use App\Domain\Entity\UserSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<UserSettings>
 */
class UserSettingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserSettings::class);
    }

    public function findOneByUser(User $user): ?UserSettings
    {
        return $this->findOneBy(['user' => $user]);
    }
}
