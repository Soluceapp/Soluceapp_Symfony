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

class MotcroiseController extends AbstractController
{
    #[Route('/activities/motcroise', name: 'app_motcroise')]
    public function index(EntityManagerInterface $entityManager): Response
    {$this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());
        $classe=$dutil->getClasse();
        return $this->render('activities/motcroise.html.twig', [
            'classe'=>$classe
       
        ]);
    }

    #[Route('/activities/motcroise/motcroise', name: 'app_motcroise2')]
    public function motcroise2(Dutil $dutil,SessionInterface $session,Scenario $Scenario,EntityManagerInterface $entityManager,Request $request): Response
    {$this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        //Création de la variable  $id_scenario pour ajuster l'affichage des scénarios en fonction de la classe
        $id_scenario=intval(htmlspecialchars($request->get('id_scenario')));
        $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());
        $classe=$dutil->getClasse();
        $Idclasse=$classe->getId();
        if($Idclasse==2){$id_scenario=$id_scenario+30;}if($Idclasse==3){$id_scenario=$id_scenario+60;}if($Idclasse==4){$id_scenario=$id_scenario+90;}
        $Scenario=$entityManager->getRepository(Scenario::class)->find($id_scenario);
        
        //vérif le scénario est déjà validé par l'utilisateur (pour limiter le nombre de participation).
        $motcroisefait=$dutil->getMotcroisefait();
        
        if(!$id_scenario==""&&$id_scenario>0&&$id_scenario<100){$motcroisefait=$motcroisefait[$id_scenario];}
        else
        {
            $this->addFlash('info','Il faut indiquer un numéro de scénario.');
            return $this->redirectToRoute('app_motcroise');  
        }
        if($motcroisefait==1)
        {
            $this->addFlash('success','Mot croisé déjà réalisé.');
            return $this->redirectToRoute('app_motcroise');  
        }    
        $session->set("id_scenario",$id_scenario);//mise en session de l'id pour récupération en résultat.

        if(isset($Scenario))
        {
        $name_scenario=$Scenario->getNameScenario();
        $lienimage=$Scenario->getLienimage();
        $lienmotcroise=$Scenario->getLienmotcroise();

        $reponsemotcroise=$Scenario->getReponsemotcroise();
        }
        else {$this->addFlash('success','Mot croisé déjà réalisé ou impossible.');return $this->redirectToRoute('app_activities');}


        return $this->render('activities/motcroise2.html.twig', [
       

            'name_scenario'=>$name_scenario,
            'lienimage'=>$lienimage,
            'lienmotcroise'=>$lienmotcroise,
            'reponsemotcroise'=>$reponsemotcroise,
        ]);
    }
}
