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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Profile::class);
    }

    /**
     * @param User $user
     * @return Profile[] Returns an array of Profile objects
     */
    public function findRecs(UserInterface $user): array
    {
        $gender = $user->getProfile()->getGender();

        return $this->createQueryBuilder('profile')
            ->where('profile.gender != :gender')
            ->andWhere('profile.user != :user')
            ->setParameter('gender', $gender)
            ->setParameter('user', $user)
            ->setMaxResults(8)
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
