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
   
    #[Route('/resultat/facturemystere', name: 'app_resultatfacture')]
    public function resultFacture( Dutil $dutil,SessionInterface $session,EntityManagerInterface $entityManager,Request $request,DonneNoteService $note): Response
    {  $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        // Récupère la solution et la réponse.
        $solution= new Nettoyeur(); $solution=$solution->nettoyeur_int(($session->get('solution')));
        $montant= new Nettoyeur(); $montant=$montant->nettoyeur_int($request->get('montant'));
        $solution_token=0;// Car clear la session.
        
       if($solution==$montant)
            {
            //Méthode complète de modification de base (récupération et affectation).
            $dutil = new Dutil();
            $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());
            $dutil->getId();

            //Vérif points max de participation = 5 sur cette activité.
            $arraylim=$dutil->getLimparticipation(); 
            if($arraylim[0]>=5){$this->addFlash('success',"Vous avez gagné le maximum de 5 points pour cette activité.");return $this->redirectToRoute('app_activities');}

            $points=$dutil->getPoints();
            $points=$points+1;
            $dutil->setPoints($points);
            $dutil->setResetToken($solution);
        
            $arraylim[0]=$arraylim[0]+1;// 0 pour factumemystère
            $dutil->setLimparticipation($arraylim);

            $entityManager->persist($dutil);
            $entityManager->flush();
            $note->donneNote($entityManager);
            $this->addFlash('success',"Vous gagnez un point");
            $session->clear();
            $solution_token=$dutil->getResetToken();
        }
        else 
        {
            //Méthode uniquement de récupération de données
            $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());
            $dutil->getId();
            $points=$dutil->getPoints();

        } 
        return $this->render('activities/resultat.html.twig',['SOL'=>$solution_token] );
    }

    #[Route('/resultat/chevaux', name: 'app_resultatchevaux')]
    public function resultChevaux(Scenario $Scenario,Dutil $dutil,SessionInterface $session,EntityManagerInterface $entityManager,Request $request,DonneNoteService $note): Response
    {  $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        // Récupère les réponses.
        $reponse1= new Nettoyeur(); $reponse1=$reponse1->nettoyeur_str($request->get('reponse1'));
        $reponse2= new Nettoyeur(); $reponse2=$reponse2->nettoyeur_str($request->get('reponse2'));
        $reponse3= new Nettoyeur(); $reponse3=$reponse3->nettoyeur_str($request->get('reponse3'));
        $reponse4= new Nettoyeur(); $reponse4=$reponse4->nettoyeur_str($request->get('reponse4'));
        $reponse5= new Nettoyeur(); $reponse5=$reponse5->nettoyeur_str($request->get('reponse5'));
        $reponse6= new Nettoyeur(); $reponse6=$reponse6->nettoyeur_str($request->get('reponse6')); 
        
        // Récupère les solutions.
        $id=new Nettoyeur(); $id=$id->nettoyeur_str($session->get('id_scenario'));
        if(isset($id))
        {
            $Scenario=$entityManager->getRepository(Scenario::class)->find($id);
            $solution1=$Scenario->getSolution1();
            $solution2=$Scenario->getSolution2();
            $solution3=$Scenario->getSolution3();
            $solution4=$Scenario->getSolution4();
            $solution5=$Scenario->getSolution5();
            $solution6=$Scenario->getSolution6();
            $tot=0;
            if($reponse1==$solution1){$tot++;}if($reponse2==$solution2){$tot++;}if($reponse3==$solution3){$tot++;}
            if($reponse4==$solution4){$tot++;}if($reponse5==$solution5){$tot++;}if($reponse6==$solution6){$tot++;}
        }
        else {$this->addFlash('success','Scénario non reconnu.');return $this->redirectToRoute('app_activities');}

       if($tot>=4)
        {        
            //Méthode complète de modification de base (récupération et affectation).
            ResultatController::pointDansBase($dutil,$entityManager, $note);

            //pour affichage résultat en template.
            $sol=1;
   
            //vérif le scénario est déjà validé par l'utilisateur (pour limiter le nombre de participation).
            $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());
            $scenariofait=$dutil->getScenariofait();
            $scenario_fait=$scenariofait[$id];
            if($scenario_fait==1)
            {
                $this->addFlash('success','Scénario déjà réalisé ou impossible.');
                return $this->redirectToRoute('app_chevaux');  
            }else{$scenariofait[$id]=1; $dutil->setScenariofait($scenariofait);
            $entityManager->persist($dutil);
            $entityManager->flush();}    

            $session->clear();
        }
        else 
        {
            //Méthode uniquement de récupération de données
            $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());
            $dutil->getId();
            $this->addFlash('success',"Pas assez de bonnes réponses.");
        }       
        return $this->render('activities/resultat.html.twig',['SOL'=>htmlspecialchars($sol)] );
    }

    #[Route('/resultat/motcroise', name: 'app_resultatmotcroise')]
    public function resultMotcroise(Scenario $Scenario,Dutil $dutil,SessionInterface $session,EntityManagerInterface $entityManager,Request $request,DonneNoteService $note): Response
    {  $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        
        // Récupère la réponse.
        $motcache= new Nettoyeur(); $motcache=$motcache->nettoyeur_str(trim(strtolower($request->get('montant'))));
        
        // Récupère les solutions.
        $id=new Nettoyeur(); $id=$id->nettoyeur_str($session->get('id_scenario'));
        if(isset($id))
        {
            $Scenario=$entityManager->getRepository(Scenario::class)->find($id);
            $reponsemotcroise=$Scenario->getReponsemotcroise();

            if($motcache==$reponsemotcroise)
                {
                ResultatController::pointDansBase($dutil,$entityManager, $note);
                //vérif le scénario est déjà validé par l'utilisateur (pour limiter le nombre de participation).
                $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());
                $motcroisefait=$dutil->getMotcroisefait();
                $motcroisefait_=$motcroisefait[$id];
                if($motcroisefait_==1)
                {
                    $this->addFlash('success','Mot croisé déjà réalisé ou impossible.');
                    return $this->redirectToRoute('app_activities');  
                }
                else
                {
                $motcroisefait[$id]=1; $dutil->setMotcroisefait($motcroisefait);
                $entityManager->persist($dutil);
                $entityManager->flush();}    
                $session->clear();
                }   
        }
        else {$this->addFlash('success','Tu as déjà gagné un point sur ce mot croisé.');return $this->redirectToRoute('app_activities');}
        return $this->render('activities/resultat.html.twig',['SOL'=>htmlspecialchars($reponsemotcroise)] );
    }

    #[Route('/resultat/cours', name: 'app_resultatcours')]
    public function resultCours(Scenario $Scenario,Dutil $dutil,SessionInterface $session,EntityManagerInterface $entityManager,Request $request,DonneNoteService $note): Response
    {  $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        
        // Récupère les réponses.
        $motcache=htmlspecialchars($request->get('montant'));

        // Récupère les solutions.
        $id=new Nettoyeur(); $id=$id->nettoyeur_str($session->get('id_scenario'));
        if(isset($id))
        {
            $Scenario=$entityManager->getRepository(Scenario::class)->find($id);
            $reponsecours=$Scenario->getReponsemotcroise();

            if($motcache==$reponsecours)
            {
        
            ResultatController::pointDansBase($dutil,$entityManager, $note);

            //vérif le scénario est déjà validé par l'utilisateur (pour limiter le nombre de participation).
            $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());
            $motcroisefait=$dutil->getMotcroisefait();
            $motcroisefait_=$motcroisefait[$id];
            if($motcroisefait_==1)
            {
            $this->addFlash('success','Mot croisé déjà réalisé ou impossible.');
            return $this->redirectToRoute('app_activities');  
            }
            else
            {
            $motcroisefait[$id]=1; $dutil->setMotcroisefait($motcroisefait);
            $entityManager->persist($dutil);
            $entityManager->flush();}    
            $session->clear();
            }
        }
        else {$this->addFlash('success','Tu as déjà gagné un point sur ce mot croisé.');return $this->redirectToRoute('app_activities');}
        return $this->render('activities/resultat.html.twig',['SOL'=>$reponsecours] );
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
