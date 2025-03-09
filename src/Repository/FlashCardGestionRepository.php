<?php

namespace App\Repository;

use App\Entity\FlashCardGestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FlashCardGestion>
 *
 * @method FlashCardGestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method FlashCardGestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method FlashCardGestion[]    findAll()
 * @method FlashCardGestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlashCardGestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FlashCardGestion::class);
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
