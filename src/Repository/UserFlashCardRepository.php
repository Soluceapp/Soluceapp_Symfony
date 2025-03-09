<?php

namespace App\Repository;

use App\Entity\UserFlashCard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Dutil;

/**
 * @extends ServiceEntityRepository<UserFlashCard>
 *
 * @method UserFlashCard|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserFlashCard|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserFlashCard[]    findAll()
 * @method UserFlashCard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserFlashCardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFlashCard::class);
    }

    public function findFlashCardsForEvaluation(Dutil $user): array
    {
        $qb = $this->createQueryBuilder('ufc')
            ->leftJoin('ufc.flashCardEco', 'eco')
            ->leftJoin('ufc.flashCardGestion', 'gestion')
            ->where('ufc.dutil = :user')
            ->andWhere('ufc.score IS NULL OR ufc.score = 0')
            ->setParameter('user', $user)
            ->orderBy('ufc.score', 'ASC') // Priorité aux scores NULL, puis 0
            ->setMaxResults(20);

        return $qb->getQuery()->getResult();
    }
}


