<?php

namespace App\Repository;

use App\Entity\ActivityStudent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ActivityStudent>
 *
 * @method ActivityStudent|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivityStudent|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivityStudent[]    findAll()
 * @method ActivityStudent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityStudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityStudent::class);
    }

//    /**
//     * @return ActivityStudent[] Returns an array of ActivityStudent objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ActivityStudent
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
