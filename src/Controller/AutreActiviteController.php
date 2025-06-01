<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Scenario;
use App\Entity\Dutil;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Security\Nettoyeur;
use App\Services\AutreActiviteService;

// Méthodes relatives à toutes les activités sauf flashcards
class AutreActiviteController extends AbstractController
{
    // Constructeur du service du controller
    private $autreActiviteService;
    public function __construct(AutreActiviteService $autreActiviteService)
    {
        $this->autreActiviteService=$autreActiviteService;
    }

    // Affiche la page d'acceuil de l'application pour sélectionner les activités
    #[Route('/activities', name: 'app_activities')]
    public function index(Dutil $dutil,EntityManagerInterface $entityManager): Response
    { 
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());
        $verif=$dutil->isVerified();
        if($verif==0) {$this->addFlash('danger','Vous devez confirmer votre mail par votre messagerie.');}

        return $this->render('activities/index.html.twig');
    }


    // Permet de choisir un jeu de petit chevaux en fonction d'un scénario
    #[Route('/activities/choix-chevaux', name: 'app_choix_chevaux')]
    public function choixPetitChevaux(): Response
    {
        // Vérifie si l'utilisateur est authentifié
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        // Récupère l'utilisateur connecté
        $user = $this->getUser();

        // Appel du service pour récupérer les scénarios
        $scenarios = $this->autreActiviteService->getScenariosByUser($user);

        // Récupère la classe de l'utilisateur (elle peut aussi être passée par le service)
        $classe = $user->getClasse();

        // Passe les scénarios au template
        return $this->render('activities/petit_chevaux_choix.html.twig', [
            'scenarios' => $scenarios, // Liste des scénarios filtrés
            'classe' => $classe,       // Classe de l'utilisateur
        ]);
    }

    // Permet d'afficher la liste des questions des petits chevaux
    #[Route('/activities/question-petit-chevaux', name: 'app_question_chevaux')]
    public function questionPetitChevaux(
        Request $request,
        AutreActiviteService $activiteService,
        SessionInterface $session
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $user = $this->getUser();

        $idScenario = Nettoyeur::nettoyeurInt((int) $request->request->get('id_scenario'));
        if (!$idScenario) {
            $this->addFlash('danger', 'Veuillez sélectionner un scénario.');
            return $this->redirectToRoute('app_choix_chevaux');
        }

        $result = $activiteService->prepareQuestionPetitChevaux($idScenario, $user, $session);

        if (isset($result['error'])) {
            $this->addFlash('danger', $result['error']);
            return $this->redirectToRoute('app_choix_chevaux');
        }

        if (isset($result['info'])) {
            $this->addFlash('success', $result['info']);
            return $this->redirectToRoute('app_choix_chevaux');
        }

        return $this->render('activities/petit_chevaux.html.twig', [
            'scenario' => $result['scenario'],
            'lienChevaux' => $result['lienChevaux'],
        ]);
    }

    // Permet d'afficher l'application de résolution d'une facture mystère
    #[Route('/activities/facture-mystere', name: 'app_facture')]
    public function factureMystere(SessionInterface $session, AutreActiviteService $activiteService): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
    
        $data = $activiteService->generateFactureMystere($session);
    
        return $this->render('activities/facture_mystere.html.twig', [
            'B00' => $data['B'][0],
            'B01' => $data['B'][1],
            'B02' => $data['B'][2],
            'B03' => $data['B'][3],
            'B04' => $data['B'][4],
    
            'C00' => $data['C'][0],
            'C01' => $data['C'][1],
            'C02' => $data['C'][2],
            'C03' => $data['C'][3],
            'C04' => $data['C'][4],
    
            'D00' => $data['D'][0],
            'D01' => $data['D'][1],
            'D02' => $data['D'][2],
            'D03' => $data['D'][3],
            'D04' => $data['D'][4],
    
            'E00' => $data['E'][0],
            'E01' => $data['E'][1],
            'E02' => $data['E'][2],
    
            'SOL' => $data['SOL'],
        ]);
    }
    
    // Permet de choisir le  mot croisé en fonction d'une liste de scénarios
    #[Route('/activities/choix-mot-croise', name: 'app_choix_mot_croise')]
    public function choixMotCroise(
        EntityManagerInterface $entityManager): Response
    {    
        // Vérifie si l'utilisateur est authentifié
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        // Récupère l'utilisateur connecté
        $dutil = $entityManager->getRepository(Dutil::class)->find($this->getUser());

        // Récupère la classe de l'utilisateur
        $classe = $dutil->getClasse();

        // Récupère les scénarios associés à la classe
        $scenarios = $entityManager->getRepository(Scenario::class)->findBy(['classe' => $classe]);
    
        return $this->render('activities/mot_croise_choix.html.twig', [
            'scenarios' => $scenarios, // Liste des scénarios filtrés
            'classe' => $classe, // Classe de l'utilisateur (première, seconde...)
       
        ]);
    }

    // Permet de faire les mots croisés
    #[Route('/activities/mot-croise', name: 'app_mot_croise')]
    public function affichageMotCroise(
        AutreActiviteService $autreActiviteService,
        SessionInterface $session,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
    
        $result = $autreActiviteService->prepareMotCroise($request, $session, $entityManager, $this->getUser());
    
        if ($result === 'missing_scenario') {
            $this->addFlash('danger', 'Veuillez sélectionner un scénario.');
            return $this->redirectToRoute('app_chevaux');
        }
    
        if ($result === 'scenario_not_found') {
            $this->addFlash('danger', 'Scénario introuvable.');
            return $this->redirectToRoute('app_choix_mot_croise');
        }
    
        if ($result === 'invalid_id') {
            $this->addFlash('info', 'Il faut indiquer un numéro de scénario.');
            return $this->redirectToRoute('app_choix_mot_croise');
        }
    
        if ($result === 'already_done') {
            $this->addFlash('success', 'Mot croisé déjà réalisé.');
            return $this->redirectToRoute('app_choix_mot_croise');
        }
    
        return $this->render('activities/mot_croise.html.twig', $result);
    }
    
    #[Route('/resultat/facture-mystere', name: 'app_resultatfacture')]
    public function resultFacture( 
        SessionInterface $session,
        EntityManagerInterface $entityManager,
        Request $request,
        AutreActiviteService $autreActiviteService
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $result = $autreActiviteService->prepareFactureMystereResultat($session, $request, $entityManager, $this->getUser());

        // Gestion d'un éventuel flash + redirection
        if (isset($result['redirect'])) {
            $this->addFlash($result['flash_type'], $result['flash_message']);
            return $this->redirectToRoute($result['redirect']);
        }

        // Flash si non redirection
        if (isset($result['flash_message'])) {
            $this->addFlash($result['flash_type'], $result['flash_message']);
        }

        return $this->render('activities/resultat.html.twig', [
            'SOL' => $result['solution_token'] ?? 0
        ]);
    }

    // Permet de calculer les points donnés par l'activité chevaux.
    #[Route(path: '/resultat/chevaux', name: 'app_resultatchevaux')]
    public function resultChevaux(
        SessionInterface $session,
        EntityManagerInterface $entityManager,
        Request $request,
        AutreActiviteService $note
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $user = $this->getUser();

        $result = $note->prepareResultatChevaux($session, $request, $entityManager, $user);

        // Gestion des messages flash
        if (isset($result['flash_message'])) {
            $this->addFlash($result['flash_type'], $result['flash_message']);
        }

        // Redirection si nécessaire
        if (isset($result['redirect'])) {
            return $this->redirectToRoute($result['redirect']);
        }

        // Nettoyage session seulement si pas déjà fait dans le service
        $session->clear();

        return $this->render('activities/resultat.html.twig', [
            'SOL' => htmlspecialchars($result['solution'] ?? 0),
        ]);
    }

    #[Route('/resultat/mot-croise', name: 'app_resultatmotcroise')]
    public function resultMotcroise(
        SessionInterface $session,
        EntityManagerInterface $entityManager,
        Request $request,
        AutreActiviteService $note
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $user = $this->getUser();
    
        $result = $note->prepareResultatMotCroise($session, $request, $entityManager, $user);
    
        if (isset($result['flash_message'])) {
            $this->addFlash($result['flash_type'], $result['flash_message']);
        }
    
        if (isset($result['redirect'])) {
            return $this->redirectToRoute($result['redirect']);
        }
    
        return $this->render('activities/resultat.html.twig', [
            'SOL' => htmlspecialchars($result['solution'] ?? ''),
        ]);
    }
    
    #[Route('/resultat/cours', name: 'app_resultatcours')]
public function resultCours(
    SessionInterface $session,
    EntityManagerInterface $entityManager,
    Request $request,
    AutreActiviteService $note
): Response {
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
    $user = $this->getUser();

    $result = $note->prepareResultatCours($session, $request, $entityManager, $user);

    if (isset($result['flash_message'])) {
        $this->addFlash($result['flash_type'], $result['flash_message']);
    }

    if (isset($result['redirect'])) {
        return $this->redirectToRoute($result['redirect']);
    }

    return $this->render('activities/resultat.html.twig', [
        'SOL' => $result['solution'] ?? '',
    ]);
}


}


