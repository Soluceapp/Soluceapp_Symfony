<?php

namespace App\Controller\Admin;

use App\Entity\ClassStudent;
use App\Entity\Dutil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\RecupnoteFormType;
use Symfony\Component\HttpFoundation\Request;

class RecupNoteController extends AbstractController
{
    #[Route('/admin/notes', name: 'app_recupnote')]
    public function recupnote(Request $request,EntityManagerInterface $entityManager, Dutil $dutil): Response
    {
        //Récupère le formulaire
        $user = new Dutil();
        $form = $this->createForm(RecupnoteFormType::class, $user);
        $form->handleRequest($request);
        $Namedomaine=$Idclasse=0;
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $Iddomaine=$form->get('id_domain')->getData();
            $Namedomaine=htmlspecialchars($Iddomaine->getId());

            $Idclasse=$form->get('classe')->getData();
            $Nameclasse=htmlspecialchars($Idclasse->getId());

            $dutil=$entityManager->getRepository(Dutil::class)->findAll();
       // dd($dutil);

            
        }
        
        return $this->render('admin/recupnote.html.twig', [
            'registrationForm' => $form->createView(),
            'dutil'=>$dutil,
            'Namedomaine'=>$Namedomaine,
            'Nameclasse'=>$Nameclasse,


        ]);
    }



}
