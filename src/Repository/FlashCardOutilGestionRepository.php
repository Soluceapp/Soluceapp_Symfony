<?php

namespace App\Repository;

use App\Entity\FlashCardOutilGestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FlashCardOutilGestion>
 *
 * @method FlashCardOutilGestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method FlashCardOutilGestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method FlashCardOutilGestion[]    findAll()
 * @method FlashCardOutilGestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlashCardOutilGestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FlashCardOutilGestion::class);
    }

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
