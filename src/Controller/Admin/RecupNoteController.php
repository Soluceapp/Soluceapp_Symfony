<?php

namespace App\Controller\Admin;

use App\Entity\ClassStudent;
use App\Entity\Dutil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\RecupnoteFormType;
use App\Form\ChangepointsFormType;
use Symfony\Component\HttpFoundation\Request;

class RecupNoteController extends AbstractController
{
    #[Route('/admin/notes', name: 'app_recupnote')]
    public function recupnote(Request $request,EntityManagerInterface $entityManager, Dutil $dutil): Response
    {$this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        //Récupère le formulaire
        $user = new Dutil();
        $form = $this->createForm(RecupnoteFormType::class, $user);
        $form->handleRequest($request);
        $form_points = $this->createForm(ChangepointsFormType::class, $user);
        $form_points->handleRequest($request);
        $Namedomaine=$Nameclasse=$points=0;


        if ($form->isSubmitted() && $form->isValid()) 
        {
            $Iddomaine=$form->get('id_domain')->getData();
            $Namedomaine=htmlspecialchars($Iddomaine->getId());

            $Idclasse=$form->get('classe')->getData();
            $Nameclasse=htmlspecialchars($Idclasse->getId());

            $dutil=$entityManager->getRepository(Dutil::class)->findAll();
        }
       
        if ($form_points->isSubmitted() && $form_points->isValid()) 
        {
            $dutil=$form_points->get('Nom')->getData();        
            $points=$dutil->getPoints();
            $points=$points+1;
            $dutil->setPoints($points);
            $entityManager->persist($dutil);
            $entityManager->flush();  
        }
           
        return $this->render('admin/recupnote.html.twig', [
            'RecupnoteFormType' => $form->createView(),
            'ChangepointsFormType' => $form_points->createView(),
            'dutil'=>$dutil,
            'Namedomaine'=>$Namedomaine,
            'Nameclasse'=>$Nameclasse,
        ]);
        
    
    }


}
