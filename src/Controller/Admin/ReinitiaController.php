<?php

namespace App\Controller\Admin;

use App\Entity\ClassStudent;
use App\Entity\DomaineStudent;
use App\Entity\Dutil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\RecupnoteFormType;
use Symfony\Component\HttpFoundation\Request;


class ReinitiaController extends AbstractController
{
    #[Route('/admin/initia', name: 'app_reinitia')]
    public function recupnote(Request $request,EntityManagerInterface $entityManager): Response
    {$this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        //Récupère le formulaire
        $user = new Dutil();
        $form = $this->createForm(RecupnoteFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            // Récupération et conversion utile du formulaire $form
            $Iddomaine=$form->get('id_domain')->getData();
            $Iddomaineverif=htmlspecialchars($Iddomaine->getId());
            $Iddomaine_int=intval($Iddomaineverif);

            $Idclasse=$form->get('classe')->getData();
            $Idclasseverif=htmlspecialchars($Idclasse->getId());
            $Idclasse_int=intval($Idclasseverif);
            
            
            $dutil=$entityManager->getRepository(Dutil::class)->findAll();
            $domaine=$entityManager->getRepository(DomaineStudent::class)->find($Iddomaineverif); 
            $classe=$entityManager->getRepository(ClassStudent::class)->find($Idclasseverif);


            // Parcourir la liste des utilisateurs de la classe et mettre à zéro leurs points
            $users = $entityManager->getRepository(Dutil::class)->findBy(['id_domain' => $domaine,'classe' => $classe]);
            
            foreach ($users as $user) {     
                $user->setPoints(0);
                $user->setNote(0);
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success',"Classe réinitialisée.");}
        }

          
        return $this->render('admin/reinitia.html.twig', [
            'ReinitiaFormType' => $form->createView(),
      
        ]);
        
    
    }


}
