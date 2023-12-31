<?php

namespace App\Controller;

use App\Entity\Dutil;
use App\Repository\DutilRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;

class ResultatController extends AbstractController 
{

    #[Route('/resultat', name: 'app_resultat')]
    public function result( Dutil $dutil,SessionInterface $session,EntityManagerInterface $entityManager,Request $request): Response
    {  $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        // Récupère la solution et la réponse.
        $solution=$session->get('solution');
        $montant=$request->get('montant');
        $solution_token=0;// Car clear la session.

       if($solution==$montant)
        {        
        //Méthode complète de modification de base (récupération et affectation).
        $dutil = new Dutil();
        $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());
        $dutil->getId();
        $points=$dutil->getPoints();
        $points=$points+1;
        $dutil->setPoints($points);
        $dutil->setResetToken($solution);
        $entityManager->persist($dutil);
        $entityManager->flush();
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

  
}
