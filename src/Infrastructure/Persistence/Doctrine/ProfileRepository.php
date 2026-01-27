<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Entity\Profile;
use App\Domain\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Profile>
 */
class ProfileRepository extends ServiceEntityRepository
{
    private const LIMIT = 8;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Profile::class);
    }

    /**
     * @param User $user
     * @return Profile[] Returns an array of Profile objects
     */
    public function findRecs(UserInterface $user, int $page = 1): array
    {
        $gender = $user->getProfile()->getGender();
        $offset = ($page - 1) * self::LIMIT;

        return $this->createQueryBuilder('profile')
            ->leftJoin(
                'App\Domain\Entity\UserReaction',
                'ur',
                'WITH',
                'ur.targetUser = profile.user AND ur.user = :user'
            )
            ->where('profile.gender != :gender')
            ->andWhere('profile.user != :user')
            ->andWhere('ur.id IS NULL')
            ->setParameter('gender', $gender)
            ->setParameter('user', $user)
            ->setFirstResult($offset)
            ->setMaxResults(self::LIMIT)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Profile[] Returns an array of Profile objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }
}
