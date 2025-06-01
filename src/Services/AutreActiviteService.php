<?php
namespace App\Services;


use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Dutil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Scenario;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AutreActiviteService extends AbstractController 
{

    private $entityManager;
    private $session;


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

    ///
    // Fonction utilisée par app_question (question des petits-chevaux)
    ///
    public function prepareQuestionPetitChevaux(int $idScenario, $user, SessionInterface $session): array
    {
        // Enregistrement du scénario en session (utiliser l'objet passé, pas $this->session)
        $session->set('id_scenario', $idScenario);
    
        // Vérification et chargement du scénario
        $scenario = $this->entityManager->getRepository(Scenario::class)->find($idScenario);
        if (!$scenario) {
            return ['error' => 'Scénario introuvable.'];
        }
    
        $dutil = $this->entityManager->getRepository(Dutil::class)->find($user);
        $scenarioFait = $dutil->getScenarioFait();
    
        if (!$idScenario || $idScenario <= 0 || $idScenario >= 100) {
            return ['error' => 'Scénario introuvable.'];
        }
    
        $indexScenario = $scenarioFait[$idScenario] ?? null;
        if ($indexScenario == 1) {
            return ['info' => 'Petits chevaux déjà réalisé. Un point a déjà été donné.'];
        }
    
        return [
            'scenario' => $scenario,
            'lienChevaux' => $scenario->getLienChevaux()
        ];
    }

    public function generateFactureMystere(SessionInterface $session): array
    {
        $x1 = rand(10, 100); $x2 = rand(10, 100); $x3 = rand(10, 100); $x4 = rand(10, 100); $x5 = rand(10, 100);
        $y1 = rand(10, 100); $y2 = rand(10, 100); $y3 = rand(10, 100); $y4 = rand(10, 100); $y5 = rand(10, 100);
        $z1 = rand(50000, 60000); $z2 = rand(50000, 60000); $z3 = rand(50000, 60000); $z4 = rand(50000, 60000); $z5 = rand(50000, 60000);
    
        $A = array_fill(0, 5, array_fill(0, 4, 1));
        $ligne = rand(0, 4);
        $colonne = rand(0, 3);
        $A[$ligne][$colonne] = 2;
    
        $B = [
            [$z2, "L'économie générale", $x2, $y2, $x2 * $y2],
            [$z1, "La responsabilité sociale de l'entreprise", $x1, $y1, $x1 * $y1],
            [$z3, "La logistique", $x5, $y5, $x5 * $y5],
            [$z4, "Précis de statistiques financières", $x4, $y4, $x4 * $y4],
            [$z5, "Sociologie et psychologie du comportement", $x3, $y3, $x3 * $y3],
        ];
    
        $C = [
            [$z1, $B[1][1], $x1 * $A[0][0], $y1 * $A[0][1], ($x1 * $A[0][0]) * ($y1 * $A[0][1])],
            [$z2, $B[0][1], $x2 * $A[3][0], $y2 * $A[3][1], ($x2 * $A[3][0]) * ($y2 * $A[3][1])],
            [$z4, $B[3][1], $x4 * $A[1][0], $y4 * $A[1][1], ($x4 * $A[1][0]) * ($y4 * $A[1][1])],
            [$z3, $B[2][1], $x5 * $A[2][0], $y5 * $A[2][1], ($x5 * $A[2][0]) * ($y5 * $A[2][1])],
            [$z5, $B[4][1], $x3 * $A[4][0], $y3 * $A[4][1], ($x3 * $A[4][0]) * ($y3 * $A[4][1])],
        ];
    
        $D = [
            [$z2, $B[0][1], $x2 * $A[1][2], $y2 * $A[1][3], ($x2 * $A[1][2]) * ($y2 * $A[1][3])],
            [$z3, $B[2][1], $x5 * $A[0][2], $y5 * $A[0][3], ($x5 * $A[0][2]) * ($y5 * $A[0][3])],
            [$z1, $B[1][1], $x1 * $A[4][2], $y1 * $A[4][3], ($x1 * $A[4][2]) * ($y1 * $A[4][3])],
            [$z5, $B[4][1], $x3 * $A[3][2], $y3 * $A[3][3], ($x3 * $A[3][2]) * ($y3 * $A[3][3])],
            [$z4, $B[3][1], $x4 * $A[2][2], $y4 * $A[2][3], ($x4 * $A[2][2]) * ($y4 * $A[2][3])],
        ];
    
        $E = [
            [array_sum(array_column($B, 4)), 0, 0],
            [array_sum(array_column($C, 4)), 0, 0],
            [array_sum(array_column($D, 4)), 0, 0],
        ];
    
        foreach ($E as &$total) {
            $total[1] = $total[0] * 0.2;
            $total[2] = $total[0] + $total[1];
        }
    
        $M = [
            [$x1, $y1, $x5, $y5],
            [$x4, $y4, $x2, $y2],
            [$x5, $y5, $x4, $y4],
            [$x2, $y2, $x3, $y3],
            [$x3, $y3, $x1, $y1],
        ];
    
        $solution = $M[$ligne][$colonne];
        $session->set("solution", $solution);
    
        return [
            'B' => $B,
            'C' => $C,
            'D' => $D,
            'E' => $E,
            'SOL' => $solution,
        ];
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