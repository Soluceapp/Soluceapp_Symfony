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

    // Permet de faire le mots croisés
    #[Route('/activities/mot-croise', name: 'app_mot_croise')]
    public function affichageMotCroise(
        Dutil $dutil,
        SessionInterface $session,
        EntityManagerInterface $entityManager,
        Request $request): Response
    {
        
    // Vérifie si l'utilisateur est authentifié et récupère l'utilisateur
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

    // Récupérer l'ID du scénario depuis le formulaire et nettoyage
    $idScenario = Nettoyeur::nettoyeurInt(intval(($request->request->get('id_scenario'))));
    if (!$idScenario) {
        $this->addFlash('danger', 'Veuillez sélectionner un scénario.');
        return $this->redirectToRoute('app_chevaux');
    }
      
    // Charger le scénario correspondant
    $scenario = $entityManager->getRepository(Scenario::class)->find($idScenario);
    if (!$scenario) {
        $this->addFlash('danger', 'Scénario introuvable.');
        return $this->redirectToRoute('app_choix_mot_croise');
    }
         
    // Récupération //mise en session de l'id pour récupération en résultat.de la variable motCroiseFait (array de 1 à 100) enregistrée précédement pour comparaison
    $dutil = $entityManager->getRepository(Dutil::class)->find($this->getUser());
    $motCroiseFait=$dutil->getMotCroiseFait();
    
    // Comparaison entre idScenario et motCroiseFait mémorisé (variable intermédiaire indexScenario)
    if(!$idScenario==""&&$idScenario>0&&$idScenario<100){$indexScenario=$motCroiseFait[$idScenario];}
        else
        {
            $this->addFlash('info','Il faut indiquer un numéro de scénario.');
            return $this->redirectToRoute('app_choix_mot_croise');  
        }
    if($indexScenario==1)
        {
            $this->addFlash('success','Mot croisé déjà réalisé.');
            return $this->redirectToRoute('app_choix_mot_croise');  
        }  
    
    // Mise en session de l'id pour récupération en résultat.
    $session->set("id_scenario",$idScenario);

    // Récupération d'information à afficher partir de l'id nettoyée et de la base.
    if(isset($scenario))
        {
        $nameScenario=$scenario->getNameScenario();
        $lienImage=$scenario->getLienImage();
        $lienMotCroise=$scenario->getLienMotCroise();
        $reponseMotCroise=$scenario->getReponseMotCroise();
        }
    else {$this->addFlash('success','Mots croisés déjà réalisé ou impossible.');return $this->redirectToRoute('app_activities');}

    return $this->render('activities/mot_croise.html.twig', [
       
            'nameScenario'=>$nameScenario,
            'lienMotCroise'=>$lienMotCroise,
            'reponseMotCroise'=>$reponseMotCroise,
        ]);
    }

    #[Route('/resultat/facture-mystere', name: 'app_resultatfacture')]
    public function resultFacture( Dutil $dutil,SessionInterface $session,EntityManagerInterface $entityManager,Request $request,AutreActiviteService $note): Response
    {  $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        // Récupère la solution et la réponse.
        $solution= new Nettoyeur(); $solution=$solution->nettoyeurInt(($session->get('solution')));
        $montant= new Nettoyeur(); $montant=$montant->nettoyeurInt($request->get('montant'));
        $solutionToken=0;// Car clear la session.
        
       if($solution==$montant)
            {
            //Méthode complète de modification de base (récupération et affectation).
            $dutil = new Dutil();
            $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());
            $dutil->getId();

            //Vérif points max de participation = 5 sur cette activité.
            $arrayLim=$dutil->getLimParticipation(); 
            if($arrayLim[0]>=5){$this->addFlash('success',"Vous avez gagné le maximum de 5 points pour cette activité.");return $this->redirectToRoute('app_activities');}
            
            $points=$dutil->getPoints();
            $points=$points+1;
            $dutil->setPoints($points);
            $dutil->setResetToken($solution);
        
            $arrayLim[0]=$arrayLim[0]+1;// 0 pour factumemystère
            $dutil->setLimparticipation($arrayLim);

            $entityManager->persist($dutil);
            $entityManager->flush();
            $note->donneNote($entityManager);
            $this->addFlash('success',"Vous gagnez un point");
            $session->clear();
            $solutionToken=$dutil->getResetToken();
        }
        else 
        {
            //Méthode uniquement de récupération de données
            $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());
            $dutil->getId();
            $points=$dutil->getPoints();

        } 
        return $this->render('activities/resultat.html.twig',['SOL'=>$solutionToken] );
    }

    // Permet de calculer les points donnés par l'activité chevaux.
    #[Route(path: '/resultat/chevaux', name: 'app_resultatchevaux')]
    public function resultChevaux(
        SessionInterface $session,
        EntityManagerInterface $entityManager,
        Request $request,
        AutreActiviteService $note
    ): Response {

    // Vérification de l'authentification de l'utilisateur    
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
    $user = $this->getUser();

    // Récupération de l'ID du scénario depuis la session
    $id = htmlspecialchars($session->get('id_scenario'));
    if (!$id) {
        $this->addFlash('danger', 'ID du scénario introuvable.');
        return $this->redirectToRoute('app_activities');
    }

    // Récupération du scénario pour comparer ses réponses
    $Scenario = $entityManager->getRepository(Scenario::class)->find($id);
    if (!$Scenario) {
        $this->addFlash('danger', 'Scénario introuvable.');
        return $this->redirectToRoute('app_activities');
    }

    // Nettoyage des réponses utilisateur et mise en array
    $reponses = [];
    for ($i = 1; $i <= 6; $i++) {
        $reponses[$i] = (new Nettoyeur())->nettoyeurStr($request->get("reponse$i"));
    }

    // Récupération des solutions dans un array
    $solutions = [];
    for ($i = 1; $i <= 6; $i++) {
        $method = "getSolution$i";
        $solutions[$i] = $Scenario->$method();
    }

    // Calcul des bonnes réponses vérification des réponses avec solution
    $tot = 0;
    for ($i = 1; $i <= 6; $i++) {
        if ($reponses[$i] == $solutions[$i]) {
            $tot++;
        }
    }

    // Donne des points si 4 réponses bonnes sur 6
    if ($tot >= 4) {
        // Attribution des points par fonction pointDansBase
        $dutil = $entityManager->getRepository(Dutil::class)->find($user);
        AutreActiviteController::pointDansBase($dutil, $entityManager, $note);

        // Utilisation de l'id récupéré en session pour l'utiliser comme index sur scenariofait de l'utilisateur
        $scenarioFait=$dutil->getScenarioFait();
        
        $indexScenario=$scenarioFait[$id];
        // Recherche si le scénario est déjà fait (1) dans indexScenario
        if($indexScenario==1)
        {
            $this->addFlash('success','Scénario déjà réalisé ou impossible.');
            return $this->redirectToRoute('app_chevaux');
        }
        // Affectation du 1 si 4 réponses possible + sauvegarde
        else{$scenarioFait[$id]=1; $dutil->setScenarioFait($scenarioFait);
            $entityManager->persist($dutil);
            $entityManager->flush();}
        
        // Vide la mémoire de la session
        $session->clear();
        $this->addFlash('success', 'Félicitations ! Vous avez réussi.');
        } 
    else {
        $this->addFlash('warning', "Pas assez de bonnes réponses.");
        }

    return $this->render('activities/resultat.html.twig', [
        'SOL' => htmlspecialchars($tot >= 4 ? 1 : 0),
    ]);
}
    #[Route('/resultat/mot-croise', name: 'app_resultatmotcroise')]
    public function resultMotcroise(Scenario $Scenario,Dutil $dutil,SessionInterface $session,EntityManagerInterface $entityManager,Request $request,AutreActiviteService $note): Response
    {  $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        
        // Récupère la réponse.
        $motCache= new Nettoyeur(); $motCache=$motCache->nettoyeurStr(trim(strtolower($request->get('montant'))));
        
        // Récupère les solutions.
        $id=new Nettoyeur(); $id=$id->nettoyeurStr($session->get('id_scenario'));
        if(isset($id))
        {
            $Scenario=$entityManager->getRepository(Scenario::class)->find($id);
            $reponseMotCroise=$Scenario->getReponseMotCroise();

            if($motCache==$reponseMotCroise)
                {
                AutreActiviteController::pointDansBase($dutil,$entityManager, $note);
                //vérif le scénario est déjà validé par l'utilisateur (pour limiter le nombre de participation).
                $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());
                $motcroisefait=$dutil->getMotCroiseFait();
                $motcroisefait_=$motcroisefait[$id];
                if($motcroisefait_==1)
                {
                    $this->addFlash('success','Mot croisé déjà réalisé ou impossible.');
                    return $this->redirectToRoute('app_activities');  
                }
                else
                {
                $motcroisefait[$id]=1; $dutil->setMotCroiseFait($motcroisefait);
                $entityManager->persist($dutil);
                $entityManager->flush();}    
                $session->clear();
                }   
        }
        else {$this->addFlash('success','Tu as déjà gagné un point sur ce mot croisé.');return $this->redirectToRoute('app_activities');}
        return $this->render(
            'activities/resultat.html.twig',
            ['SOL'=>htmlspecialchars($reponseMotCroise)] );
    }

    #[Route('/resultat/cours', name: 'app_resultatcours')]
    public function resultCours(Scenario $Scenario,Dutil $dutil,SessionInterface $session,EntityManagerInterface $entityManager,Request $request,AutreActiviteService $note): Response
    {  $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        
        // Récupère les réponses.
        $motCache=htmlspecialchars($request->get('montant'));

        // Récupère les solutions.
        $id=new Nettoyeur(); $id=$id->nettoyeurStr($session->get('id_scenario'));
        if(isset($id))
        {
            $Scenario=$entityManager->getRepository(Scenario::class)->find($id);
            $reponSecours=$Scenario->getReponseMotCroise();

            if($motCache==$reponSecours)
            {
        
            AutreActiviteController::pointDansBase($dutil,$entityManager, $note);

            //vérif le scénario est déjà validé par l'utilisateur (pour limiter le nombre de participation).
            $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());
            $motCroiseFait=$dutil->getMotCroiseFait();
            $motCroiseFait_=$motCroiseFait[$id];
            if($motCroiseFait_==1)
            {
            $this->addFlash('success','Mot croisé déjà réalisé ou impossible.');
            return $this->redirectToRoute('app_activities');  
            }
            else
            {
            $motCroiseFait[$id]=1; $dutil->setMotCroiseFait($motCroiseFait);
            $entityManager->persist($dutil);
            $entityManager->flush();}    
            $session->clear();
            }
        }
        else {$this->addFlash('success','Tu as déjà gagné un point sur ce mot croisé.');return $this->redirectToRoute('app_activities');}
        return $this->render('activities/resultat.html.twig',['SOL'=>$reponSecours] );
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


}


