<?php
namespace App\Services;


use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Dutil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Scenario;use App\Entity\ClassStudent;
use App\Form\TestForm;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Security\Nettoyeur;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\FormFactoryInterface;

class AutreActiviteService extends AbstractController 
{

    private $entityManager;
    private $session;


    public function __construct(EntityManagerInterface $entityManager,private FormFactoryInterface $formFactory)
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
    
    public function prepareMotCroise(
        Request $request,
        SessionInterface $session,
        EntityManagerInterface $entityManager,
        UserInterface $user
    ): array|string {
        // Récupérer l'ID du scénario depuis le formulaire et nettoyage
        $idScenario = Nettoyeur::nettoyeurInt(intval($request->request->get('id_scenario')));
        if (!$idScenario) {
            return 'missing_scenario';
        }

        // Charger le scénario correspondant
        $scenario = $entityManager->getRepository(Scenario::class)->find($idScenario);
        if (!$scenario) {
            return 'scenario_not_found';
        }

        // Récupération de l'objet utilisateur Dutil
        $dutil = $entityManager->getRepository(Dutil::class)->find($user);
        $motCroiseFait = $dutil->getMotCroiseFait();

        if ($idScenario > 0 && $idScenario < 100) {
            $indexScenario = $motCroiseFait[$idScenario] ?? 0;
        } else {
            return 'invalid_id';
        }

        if ($indexScenario == 1) {
            return 'already_done';
        }

        // Mise en session de l’ID du scénario
        $session->set("id_scenario", $idScenario);

        // Préparer les données à transmettre à la vue
        return [
            'nameScenario' => $scenario->getNameScenario(),
            'lienMotCroise' => $scenario->getLienMotCroise(),
            'reponseMotCroise' => $scenario->getReponseMotCroise(),
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

    //
    //Méthode complète de modification de base (récupération et affectation).
    //
    public function pointDansBase(Dutil $dutil,EntityManagerInterface $entityManager,AutreActiviteService $note):void
    {
        $dutil = new Dutil();
        $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());
        $dutil->getId();
        $points=$dutil->getPoints();
        $points=$points+1;
        $dutil->setPoints($points);
        $entityManager->persist($dutil);
        $entityManager->flush();
        $note->donneNote($entityManager);
        $this->addFlash('success',"Vous gagnez un point");
    }

    public function prepareFactureMystereResultat(
        SessionInterface $session,
        Request $request,
        EntityManagerInterface $entityManager,
        UserInterface $user
    ): array {
        $solution = Nettoyeur::nettoyeurInt($session->get('solution'));
        $montant = Nettoyeur::nettoyeurInt($request->get('montant'));

        $solutionToken = 0;

        $dutil = $entityManager->getRepository(Dutil::class)->find($user);

        if ($solution === $montant) {
            $arrayLim = $dutil->getLimParticipation();

            if ($arrayLim[0] >= 5) {
                return [
                    'redirect' => 'app_activities',
                    'flash_type' => 'success',
                    'flash_message' => 'Vous avez gagné le maximum de 5 points pour cette activité.',
                ];
            }

            $dutil->setPoints($dutil->getPoints() + 1);
            $dutil->setResetToken($solution);

            $arrayLim[0] += 1; // index 0 = facture mystère
            $dutil->setLimparticipation($arrayLim);

            $entityManager->persist($dutil);
            $entityManager->flush();

            $solutionToken = $dutil->getResetToken();

            $session->clear();

            return [
                'flash_type' => 'success',
                'flash_message' => 'Vous gagnez un point',
                'solution_token' => $solutionToken
            ];
        } else {
            // Si la réponse est incorrecte, pas de changement, juste afficher le résultat
            return ['solution_token' => $solutionToken];
        }
    }

    public function prepareResultatChevaux(
        SessionInterface $session,
        Request $request,
        EntityManagerInterface $entityManager,
        UserInterface $user
    ): array {
        $id = Nettoyeur::nettoyeurStr($session->get('id_scenario'));
    
        if (!$id) {
            return [
                'redirect' => 'app_activities',
                'flash_type' => 'success',
                'flash_message' => 'Tu as déjà gagné un point sur ce petit-cheveaux.',
            ];
        }
    
        $scenario = $entityManager->getRepository(Scenario::class)->find($id);
        if (!$scenario) {
            return [
                'redirect' => 'app_activities',
                'flash_type' => 'danger',
                'flash_message' => 'Scénario introuvable.',
            ];
        }
    
        // Récupération et traitement des solutions
        $reponseAttendue1 = $scenario->getSolution1();
        $reponseAttendue2 = $scenario->getSolution2();
        $reponseAttendue3 = $scenario->getSolution3();
        $reponseAttendue4 = $scenario->getSolution4();
        $reponseAttendue5 = $scenario->getSolution5();
        $reponseAttendue6 = $scenario->getSolution6();

        $reponseUtilisateur1 = Nettoyeur::nettoyeurStr(trim(strtolower($request->get('reponse1'))));
        $reponseUtilisateur2 = Nettoyeur::nettoyeurStr(trim(strtolower($request->get('reponse2'))));
        $reponseUtilisateur3 = Nettoyeur::nettoyeurStr(trim(strtolower($request->get('reponse3'))));
        $reponseUtilisateur4 = Nettoyeur::nettoyeurStr(trim(strtolower($request->get('reponse4'))));
        $reponseUtilisateur5 = Nettoyeur::nettoyeurStr(trim(strtolower($request->get('reponse5'))));
        $reponseUtilisateur6 = Nettoyeur::nettoyeurStr(trim(strtolower($request->get('reponse6'))));
    
        $soltot=0;
        if ($reponseAttendue1 === $reponseUtilisateur1) {$soltot++;}
        if ($reponseAttendue2 === $reponseUtilisateur2) {$soltot++;}
        if ($reponseAttendue3 === $reponseUtilisateur3) {$soltot++;}
        if ($reponseAttendue4 === $reponseUtilisateur4) {$soltot++;}
        if ($reponseAttendue5 === $reponseUtilisateur5) {$soltot++;}
        if ($reponseAttendue6 === $reponseUtilisateur6) {$soltot++;}

        if ($soltot>=4) {
            $dutil = $entityManager->getRepository(Dutil::class)->find($user);
            $scenarioFait = $dutil->getScenarioFait();
            $dejaFait = $scenarioFait[$id] ?? 0;
    
            if ($dejaFait === 1) {
                return [
                    'redirect' => 'app_activities',
                    'flash_type' => 'success',
                    'flash_message' => 'Mot croisé déjà réalisé ou impossible.',
                ];
            }
    
            AutreActiviteService::pointDansBase($dutil, $entityManager, $this);
    
            $scenarioFait[$id] = 1;
            $dutil->setScenarioFait($scenarioFait);
            $entityManager->persist($dutil);
            $entityManager->flush();
    
            $session->clear();
    
            return [
                'solution' => 1,
            ];
        }
    
        return [
            'solution' => $reponseAttendue1,
        ];
    }

    public function prepareResultatMotCroise(
        SessionInterface $session,
        Request $request,
        EntityManagerInterface $entityManager,
        UserInterface $user
    ): array {
        $id = Nettoyeur::nettoyeurStr($session->get('id_scenario'));
    
        if (!$id) {
            return [
                'redirect' => 'app_activities',
                'flash_type' => 'success',
                'flash_message' => 'Tu as déjà gagné un point sur ce mot croisé.',
            ];
        }
    
        $scenario = $entityManager->getRepository(Scenario::class)->find($id);
        if (!$scenario) {
            return [
                'redirect' => 'app_activities',
                'flash_type' => 'danger',
                'flash_message' => 'Scénario introuvable.',
            ];
        }
    
        $reponseAttendue = $scenario->getReponseMotCroise();
        $reponseUtilisateur = Nettoyeur::nettoyeurStr(trim(strtolower($request->get('montant'))));
    
        if ($reponseUtilisateur === $reponseAttendue) {
            $dutil = $entityManager->getRepository(Dutil::class)->find($user);
            $motcroiseFait = $dutil->getMotCroiseFait();
            $dejaFait = $motcroiseFait[$id] ?? 0;
    
            if ($dejaFait === 1) {
                return [
                    'redirect' => 'app_activities',
                    'flash_type' => 'success',
                    'flash_message' => 'Mot croisé déjà réalisé ou impossible.',
                ];
            }
    
            AutreActiviteService::pointDansBase($dutil, $entityManager, $this);
    
            $motcroiseFait[$id] = 1;
            $dutil->setMotCroiseFait($motcroiseFait);
            $entityManager->persist($dutil);
            $entityManager->flush();
    
            $session->clear();
    
            return [
                'solution' => $reponseAttendue,
            ];
        }
    
        return [
            'solution' => $reponseAttendue,
        ];
    }

    public function prepareResultatCours(
        SessionInterface $session,
        Request $request,
        EntityManagerInterface $entityManager,
        UserInterface $user
    ): array {
        $id = Nettoyeur::nettoyeurStr($session->get('id_scenario'));
    
        if (!$id) {
            return [
                'redirect' => 'app_activities',
                'flash_type' => 'success',
                'flash_message' => 'Tu as déjà gagné un point sur ce cours.',
            ];
        }
    
        $scenario = $entityManager->getRepository(Scenario::class)->find($id);
        if (!$scenario) {
            return [
                'redirect' => 'app_activities',
                'flash_type' => 'danger',
                'flash_message' => 'Scénario introuvable.',
            ];
        }
    
        $reponseAttendue = $scenario->getReponseMotCroise(); 
        $reponseUtilisateur = htmlspecialchars($request->get('montant'));
    
        if ($reponseUtilisateur === $reponseAttendue) {
            $dutil = $entityManager->getRepository(Dutil::class)->find($user);
            $coursFait = $dutil->getMotCroiseFait(); 
            $dejaFait = $coursFait[$id] ?? 0;
    
            if ($dejaFait === 1) {
                return [
                    'redirect' => 'app_activities',
                    'flash_type' => 'success',
                    'flash_message' => 'Mot croisé déjà réalisé ou impossible.',
                ];
            }
    
            AutreActiviteService::pointDansBase($dutil, $entityManager, $this);
    
            $coursFait[$id] = 1;
            $dutil->setMotCroiseFait($coursFait);
            $entityManager->persist($dutil);
            $entityManager->flush();
    
            $session->clear();
        }
    
        return [
            'solution' => $reponseAttendue,
        ];
    }
    
    public function prepareComptaFacile(
        SessionInterface $session,
        Request $request,
        EntityManagerInterface $entityManager,
        UserInterface $user
    ): array {
        // Récupération des données utiles des bases (en prenant en compte les non nullable pour l'enregistrement)
        $scenario = new Scenario();
        $defaultClass = $entityManager->getRepository(ClassStudent::class)->find(1);
        if ($defaultClass) {$scenario->setClasse($defaultClass);}

        // Création du formulaire
        $form = $this->formFactory->create(TestForm::class, $scenario);
        $form->handleRequest($request);
    
        // Vérification du formulaire 
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($scenario);
            $entityManager->flush();
    
            return [
                'flash_type' => 'success',
                'flash_message' => 'Scénario enregistré',
                'redirect' => 'app_test_view', // ou null pour rester sur place
            ];
        }
    
        $scenarios = $entityManager->getRepository(Scenario::class)
            ->findBy([], ['id' => 'DESC']);
    
        return [
            'contexte' => [
                'scenarios' => $scenarios,
                'form' => $form->createView(),
            ]
        ];
    }
    

}