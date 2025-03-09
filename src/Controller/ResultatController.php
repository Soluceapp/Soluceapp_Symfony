<?php

namespace App\Controller;

use App\Entity\Dutil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Scenario;
use App\Services\DonneNoteService;
use App\Security\Nettoyeur;

//
// Méthodes utilisées lors du succès d'une activité d'un élève
//
class ResultatController extends AbstractController 
{
   
    #[Route('/resultat/facture_mystere', name: 'app_resultatfacture')]
    public function resultFacture( Dutil $dutil,SessionInterface $session,EntityManagerInterface $entityManager,Request $request,DonneNoteService $note): Response
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
        DonneNoteService $note
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
        ResultatController::pointDansBase($dutil, $entityManager, $note);

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
    public function resultMotcroise(Scenario $Scenario,Dutil $dutil,SessionInterface $session,EntityManagerInterface $entityManager,Request $request,DonneNoteService $note): Response
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
                ResultatController::pointDansBase($dutil,$entityManager, $note);
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
    public function resultCours(Scenario $Scenario,Dutil $dutil,SessionInterface $session,EntityManagerInterface $entityManager,Request $request,DonneNoteService $note): Response
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
        
            ResultatController::pointDansBase($dutil,$entityManager, $note);

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
    public function pointDansBase(Dutil $dutil,EntityManagerInterface $entityManager,DonneNoteService $note):void
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
