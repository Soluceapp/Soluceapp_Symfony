<?php

namespace App\Repository;

use App\Entity\FlashCardEco;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FlashCardEco>
 *
 * @method FlashCardEco|null find($id, $lockMode = null, $lockVersion = null)
 * @method FlashCardEco|null findOneBy(array $criteria, array $orderBy = null)
 * @method FlashCardEco[]    findAll()
 * @method FlashCardEco[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlashCardEcoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FlashCardEco::class);
    }

 
 // Utile pour la présentation des questions
 public function findAllByClassId(int $idClasse): array
 {
     $results = $this->createQueryBuilder('f')
         ->where('f.classe = :idClasse')
         ->setParameter('idClasse', $idClasse)
         ->getQuery()
         ->getResult();
 
     shuffle($results); // Mélange les résultats côté PHP
     return $results;
 }

}