<?php

namespace App\Repository;

use App\Entity\Dutil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;

/**
 * @extends ServiceEntityRepository<Dutil>
* @implements PasswordUpgraderInterface<Dutil>
 *
 * @method Dutil|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dutil|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dutil[]    findAll()
 * @method Dutil[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DutilRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dutil::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Dutil) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

   /*
    public function trouveclassedomaine(EntityManagerInterface $entityManager)
    {
        $entityManager->getRepository(Dutil::class)->find($this->getUser());
        $qb =$this->createQueryBuilder('c');
        $qb 
        ->OrderBy('c.Prenom', 'ASC')
        ->andWhere('c.id_domain = :transmet')
        //->andWhere('c.classe = :transmet2')
        ->setParameters('transmet',1);
        //->setParameters(new ArrayCollection([new Parameter('transmet', 1),new Parameter('transmet2', 2)]));

       return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Dutil[] Returns an array of Dutil objects
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

//    public function findOneBySomeField($value): ?Dutil
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
