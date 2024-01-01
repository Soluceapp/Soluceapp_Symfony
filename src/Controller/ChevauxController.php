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

class ChevauxController extends AbstractController
{
    #[Route('/activities/chevaux', name: 'app_chevaux')]
    public function chevaux(): Response
    { $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        
  
        return $this->render('activities/chevaux.html.twig', [
 
               ]);
    }

    #[Route('/activities/question', name: 'app_question')]
    public function question(Dutil $dutil,SessionInterface $session,Scenario $Scenario,EntityManagerInterface $entityManager,Request $request): Response
    { $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $id=$request->get('id_scenario');
        $Scenario=$entityManager->getRepository(Scenario::class)->find($id);
        //vérif le scénario est déjà validé par l'utilisateur (pour limiter le nombre de participation).
        $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());
        $scenariofait=$dutil->getScenariofait();
        $scenario_fait=$scenariofait[$id];
        if($scenario_fait==1)
        {
            $this->addFlash('success','Scénario déjà réalisé ou impossible.');
            return $this->redirectToRoute('app_chevaux');  
        }    

        $session->set("id_scenario",$id);//mise en session de l'id pour récupération en résultat.

        if(isset($Scenario))
        {
        $name_scenario=$Scenario->getNameScenario();
        $lienimage=$Scenario->getLienimage();
        $lienchevaux=$Scenario->getLienchevaux();

        $question1=$Scenario->getQuestion1();
        $question2=$Scenario->getQuestion2();
        $question3=$Scenario->getQuestion3();
        $question4=$Scenario->getQuestion4();
        $question5=$Scenario->getQuestion5();
        $question6=$Scenario->getQuestion6();
        

        $Reponse1=$Scenario->getReponse1();
        $Reponse2=$Scenario->getReponse2();
        $Reponse3=$Scenario->getReponse3();
        $Reponse4=$Scenario->getReponse4();
        $Reponse5=$Scenario->getReponse5();
        $Reponse6=$Scenario->getReponse6();
        }
        else {$this->addFlash('success','Scénario déjà réalisé ou impossible.');return $this->redirectToRoute('app_activities');}


        return $this->render('activities/question.html.twig', [

            'name_scenario'=>$name_scenario,
            'lienimage'=>$lienimage,
            'lienchevaux'=>$lienchevaux,
 
            'Q1'=>$question1,
            'Q2'=>$question2,
            'Q3'=>$question3,
            'Q4'=>$question4,
            'Q5'=>$question5,
            'Q6'=>$question6,

            'R1_1'=>$Reponse1[0],
            'R1_2'=>$Reponse1[1],
            'R1_3'=>$Reponse1[2],
            'R1_4'=>$Reponse1[3],

            'R2_1'=>$Reponse2[0],
            'R2_2'=>$Reponse2[1],
            'R2_3'=>$Reponse2[2],
            'R2_4'=>$Reponse2[3],

            'R3_1'=>$Reponse3[0],
            'R3_2'=>$Reponse3[1],
            'R3_3'=>$Reponse3[2],
            'R3_4'=>$Reponse3[3],

            'R4_1'=>$Reponse4[0],
            'R4_2'=>$Reponse4[1],
            'R4_3'=>$Reponse4[2],
            'R4_4'=>$Reponse4[3],

            'R5_1'=>$Reponse5[0],
            'R5_2'=>$Reponse5[1],
            'R5_3'=>$Reponse5[2],
            'R5_4'=>$Reponse5[3],

            'R6_1'=>$Reponse6[0],
            'R6_2'=>$Reponse6[1],
            'R6_3'=>$Reponse6[2],
            'R6_4'=>$Reponse6[3],
        ]);
    }

}


