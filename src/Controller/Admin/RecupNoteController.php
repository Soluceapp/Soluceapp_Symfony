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
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class RecupNoteController extends AbstractController
{
    #[Route('/admin/notes', name: 'app_recupnote')]
    public function recupnote(Request $request,EntityManagerInterface $entityManager, Dutil $dutil,SessionInterface $session): Response
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

            //Utile pour classifier en changepointsform
            $session = $request->getSession(); 
            $session->set('id_domain', $Iddomaine_int);
            $session->set('id_classe', $Idclasse_int);
            
           
        }else{$Idclasse_int=1;$Iddomaineverif=$Idclasseverif=$points=0;}

        // création du $form_points par utilisation de données en session. 
        $Idclasse_int = $session->get('id_classe');
        $Iddomain_int = $session->get('id_domain');
        $form_points = $this->createForm(ChangepointsFormType::class, $user,array('id_classe'=>$Idclasse_int,'id_domain'=>$Iddomain_int));
        $form_points->handleRequest($request);

        if ($form_points->isSubmitted() && $form_points->isValid()) 
        {
            $dutil=$form_points->get('Nom')->getData(); 
            $variapoint=$form_points->get('points')->getData();;          
            $points=$dutil->getPoints();
            $points=$points+$variapoint;
            $dutil->setPoints($points);           

            // Pas possible d'utiliser donnenoteservice : ? firewall.
            $note=$dutil->getNote();
            $points=$dutil->getPoints();
            if($points<=4){$note=$points;}else{$note=4+(($points-4)*0.25);} 
            $dutil->setNote($note);

            $entityManager->persist($dutil);
            $entityManager->flush();
            
        }
           
        return $this->render('admin/recupnote.html.twig', [
            'RecupnoteFormType' => $form->createView(),
            'ChangepointsFormType' => $form_points->createView(),
            'dutil'=>$dutil,
            'Iddomaineverif'=>$Iddomaineverif,
            'Idclasseverif'=>$Idclasseverif,
        ]);
        
    
    }


}
