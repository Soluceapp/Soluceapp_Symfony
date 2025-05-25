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

    #[Route('/activities', name: 'app_activities')]
    public function index(Dutil $dutil,EntityManagerInterface $entityManager): Response
    { 
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());
        $verif=$dutil->isVerified();
        if($verif==0) {$this->addFlash('danger','Vous devez confirmer votre mail par votre messagerie.');}

        return $this->render('activities/index.html.twig');
    }


    // Permet de choisir le scénario de petits chevaux
    #[Route('/activities/chevaux', name: 'app_chevaux')]
    public function chevaux(
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

    // Passe les scénarios au template
    return $this->render('activities/chevaux.html.twig', [
        'scenarios' => $scenarios, // Liste des scénarios filtrés
        'classe' => $classe, // Classe de l'utilisateur
    ]);
}

    // Permet d'afficher la liste des questions des petits chevaux
    #[Route('/activities/question_petit_chevaux', name: 'app_question')]
    public function question(
        Request $request, 
        EntityManagerInterface $entityManager, 
        SessionInterface $session
    ): Response {

    // Vérifie si l'utilisateur est authentifié et récupère l'utilisateur
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
    $user = $this->getUser();

    // Récupérer l'ID du scénario depuis le formulaire (récupération nettoyée)
    $idScenario = Nettoyeur::nettoyeurInt(intval(($request->request->get('id_scenario'))));
    if (!$idScenario) {
        $this->addFlash('danger', 'Veuillez sélectionner un scénario.');
        return $this->redirectToRoute('app_chevaux');
    }

    // Charger le scénario correspondant
    $scenario = $entityManager->getRepository(Scenario::class)->find($idScenario);
    if (!$scenario) {
        $this->addFlash('danger', 'Scénario introuvable.');
        return $this->redirectToRoute('app_chevaux');
    }

    //Récupération de la variable scenarioFait (array de 1 à 100) enregistrée précédement pour comparaison
    $dutil = $entityManager->getRepository(Dutil::class)->find($this->getUser());
    $scenarioFait=$dutil->getScenarioFait();

    // Comparaison entre idScenario et scenariofait mémorisé (variable intermédiaire indexScenario)
    if(!$idScenario==""&&$idScenario>0&&$idScenario<100){$indexScenario=$scenarioFait[$idScenario];}
    else
    {
        $this->addFlash('info','Scénario introuvable.');
        $session->clear();
        return $this->redirectToRoute('app_chevaux');
    }
    if($indexScenario==1)
    {
        $this->addFlash('success','Petits chevaux déjà réalisé. Un point a déjà été donné.');
        return $this->redirectToRoute('app_chevaux');
    }

    // Mise en session du scénario sélectionné
    $session->set('id_scenario', $idScenario);

    // Récupération du lien learningapp et affichage tiré de la base
    $lienChevaux=$scenario->getLienChevaux();

    // Redirection vers la vue des questions
    return $this->render('activities/chevaux_2.html.twig', [
        'scenario' => $scenario,
        'lienChevaux'=>$lienChevaux,
        ]);
    }


    #[Route('/activities/facture_mystere', name: 'app_facture')]
  
    public function facturemystere(SessionInterface $session): Response
    { 

       // Génère des données aléatoires de matrice
       $x1=rand(10,100);$x2=rand(10,100);$x3=rand(10,100);$x4=rand(10,100);$x5=rand(10,100);
       $y1=rand(10,100);$y2=rand(10,100);$y3=rand(10,100);$y4=rand(10,100);$y5=rand(10,100);
       $z1=rand(50000,60000);$z2=rand(50000,60000);$z3=rand(50000,60000);$z4=rand(50000,60000);$z5=rand(50000,60000);
       
       // Crée une matrice identitaire à modifier selon un hasard
       $p1=1;$p2=1;$p3=1;$p4=1;$p5=1;
       $q1=1;$q2=1;$q3=1;$q4=1;$q5=1;
       $r1=1;$r2=1;$r3=1;$r4=1;$r5=1;
       $s1=1;$s2=1;$s3=1;$s4=1;$s5=1;
       
       // Permet de tirer au hasard un chiffre des documents commerciaux dans A
       $ligne=rand(0,4);
       $colonne=rand(0,3);
       
       $A= array();
       $A[0] = array($p1,$q1,$r1,$s1);
       $A[1] = array($p2,$q2,$r2,$s2);
       $A[2] = array($p3,$q3,$r3,$s3);
       $A[3] = array($p4,$q4,$r4,$s4);
       $A[4] = array($p5,$q5,$r5,$s5);
       $A[$ligne][$colonne]=2;
       
       
       // Génère le bon de commande
       $B= array();
       $B[0] = array($z2,"L'économie générale",$x2,$y2,$x2*$y2);
       $B[1] = array($z1,"La responsabilité sociale de l'entreprise",$x1,$y1,$x1*$y1);
       $B[2] = array($z3,"La logistique",$x5,$y5,$x5*$y5);
       $B[3] = array($z4,"Précis de statistiques financières",$x4,$y4,$x4*$y4);
       $B[4] = array($z5,"Sociologie et psychologie du comportement",$x3,$y3,$x3*$y3);
       
       
       // Génère le bon de livraison
       $C= array();
       $C[0] = array($z1,"La responsabilité sociale de l'entreprise",$x1*$A[0][0],$y1*$A[0][1],($x1*$A[0][0])*($y1*$A[0][1]));
       $C[1] = array($z2,"L'économie générale",$x2*$A[3][0],$y2*$A[3][1],($x2*$A[3][0])*($y2*$A[3][1]));
       $C[2] = array($z4,"Précis de statistiques financières",$x4*$A[1][0],$y4*$A[1][1],($x4*$A[1][0])*($y4*$A[1][1]));
       $C[3] = array($z3,"La logistique",$x5*$A[2][0],$y5*$A[2][1],($x5*$A[2][0])*($y5*$A[2][1]));
       $C[4] = array($z5,"Sociologie et psychologie du comportement",$x3*$A[4][0],$y3*$A[4][1],($x3*$A[4][0])*($y3*$A[4][1]));
       
       
       // Génère la facture
       $D= array();
       $D[0] = array($z2,"L'économie générale",$x2*$A[1][2],$y2*$A[1][3],($x2*$A[1][2])*($y2*$A[1][3]));
       $D[1] = array($z3,"La logistique",$x5*$A[0][2],$y5*$A[0][3],($x5*$A[0][2])*($y5*$A[0][3]));
       $D[2] = array($z1,"La responsabilité sociale de l'entreprise",$x1*$A[4][2],$y1*$A[4][3],($x1*$A[4][2])*($y1*$A[4][3]));
       $D[3] = array($z5,"Sociologie et psychologie du comportement",$x3*$A[3][2],$y3*$A[3][3],($x3*$A[3][2])*($y3*$A[3][3]));
       $D[4] = array($z4,"Précis de statistiques financières",$x4*$A[2][2],$y4*$A[2][3],($x4*$A[2][2])*($y4*$A[2][3]));
       
       // Génère les totaux
       $E= array();
       $E[0] = array($B[0][4]+$B[1][4]+$B[2][4]+$B[3][4]+$B[4][4],1,1);
       $E[1] = array($C[0][4]+$C[1][4]+$C[2][4]+$C[3][4]+$C[4][4],1,1);
       $E[2] = array($D[0][4]+$D[1][4]+$D[2][4]+$D[3][4]+$D[4][4],1,1);
       
       $E[0][1] = (0.2*$E[0][0]);
       $E[1][1] = (0.2*$E[1][0]);
       $E[2][1] = (0.2*$E[2][0]);
       $E[0][2] = ($E[0][0]+$E[0][1]);
       $E[1][2] = ($E[1][0]+$E[1][1]);
       $E[2][2] = ($E[2][0]+$E[2][1]);
       
       // Détermine le montant qui devrait se trouver à la place de l'erreur $solution
       
       $M = array();
       $M[0] = array($x1,$y1,$x5,$y5);
       $M[1] = array($x4,$y4,$x2,$y2);
       $M[2] = array($x5,$y5,$x4,$y4);
       $M[3] = array($x2,$y2,$x3,$y3);
       $M[4] = array($x3,$y3,$x1,$y1);
       
       $sol=$M[$ligne][$colonne]; 
       $session->set("solution",$sol);

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        return $this->render('activities/facture_mystere.html.twig',
        ['B00'=> $B[0],
        'B01'=> $B[1],
        'B02'=> $B[2],
        'B03'=> $B[3],
        'B04'=> $B[4],
        'E00'=> $E[0],
        'C00'=> $C[0],
        'C01'=> $C[1],
        'C02'=> $C[2],
        'C03'=> $C[3],
        'C04'=> $C[4],
        'E01'=> $E[1],
        'D00'=> $D[0],
        'D01'=> $D[1],
        'D02'=> $D[2],
        'D03'=> $D[3],
        'D04'=> $D[4],
        'E02'=> $E[2],
        'SOL'=> $sol
        ]
    
    );
    }

    // Permet de choisir le scénario des mots croisés
    #[Route('/activities/mot_croise', name: 'app_mot_croise')]
    public function index2(
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
    
        return $this->render('activities/mot_croise.html.twig', [
            'scenarios' => $scenarios, // Liste des scénarios filtrés
            'classe' => $classe, // Classe de l'utilisateur (première, seconde...)
       
        ]);
    }

    // Permet de faire le mots croisés
    #[Route('/activities/mot_croise/mot_croise', name: 'app_mot_croise_2')]
    public function motCroise2(
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
        return $this->redirectToRoute('app_motcroise');
    }
         
    // Récupération //mise en session de l'id pour récupération en résultat.de la variable motCroiseFait (array de 1 à 100) enregistrée précédement pour comparaison
    $dutil = $entityManager->getRepository(Dutil::class)->find($this->getUser());
    $motCroiseFait=$dutil->getMotCroiseFait();
    
    // Comparaison entre idScenario et motCroiseFait mémorisé (variable intermédiaire indexScenario)
    if(!$idScenario==""&&$idScenario>0&&$idScenario<100){$indexScenario=$motCroiseFait[$idScenario];}
        else
        {
            $this->addFlash('info','Il faut indiquer un numéro de scénario.');
            return $this->redirectToRoute('app_mot_croise');  
        }
    if($indexScenario==1)
        {
            $this->addFlash('success','Mot croisé déjà réalisé.');
            return $this->redirectToRoute('app_mot_croise');  
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

    return $this->render('activities/mot_croise_2.html.twig', [
       
            'nameScenario'=>$nameScenario,
            'lienMotCroise'=>$lienMotCroise,
            'reponseMotCroise'=>$reponseMotCroise,
        ]);
    }

    #[Route('/resultat/facture_mystere', name: 'app_resultatfacture')]
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
    #[Route('/resultat/mot_croise', name: 'app_resultatmotcroise')]
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


