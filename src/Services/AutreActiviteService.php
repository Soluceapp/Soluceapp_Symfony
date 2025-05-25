<?php
namespace App\Services;


use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Dutil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Scenario;

class AutreActiviteService extends AbstractController 
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getScenariosByUser($user): array
    {
        // Récupère l'utilisateur connecté
        $dutil = $this->entityManager->getRepository(Dutil::class)->find($user);

        if (!$dutil) {
            throw new \Exception("Utilisateur non trouvé");
        }

        // Récupère la classe de l'utilisateur
        $classe = $dutil->getClasse();

        // Récupère les scénarios associés à la classe
        $scenarios = $this->entityManager->getRepository(Scenario::class)->findBy(['classe' => $classe]);

        return $scenarios;
    }

    public function donneNote(EntityManagerInterface $entityManager):void
    {
        $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());
        $note=$dutil->getNote();
        $points=$dutil->getPoints();
        if($points<=4)
        {
            $note=$points;
        }
        else
        {
            $note=4+(($points-4)*0.25);
        }

        $dutil->setNote($note);
        $entityManager->persist($dutil);
        $entityManager->flush();    
    }

   
}