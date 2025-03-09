<?php

namespace App\Repository;

use App\Entity\ClassStudent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClassStudent>
 *
 * @method ClassStudent|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClassStudent|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClassStudent[]    findAll()
 * @method ClassStudent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClassStudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClassStudent::class);
    }

//    /**
//     * @return ClassStudent[] Returns an array of ClassStudent objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ClassStudent
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
