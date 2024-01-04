<?php

namespace App\Repository;

use App\Entity\DomaineStudent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DomaineStudent>
 *
 * @method DomaineStudent|null find($id, $lockMode = null, $lockVersion = null)
 * @method DomaineStudent|null findOneBy(array $criteria, array $orderBy = null)
 * @method DomaineStudent[]    findAll()
 * @method DomaineStudent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DomaineStudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DomaineStudent::class);
    }

//    /**
//     * @return DomaineStudent[] Returns an array of DomaineStudent objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DomaineStudent
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
