<?php

namespace App\Controller\Admin;

use App\Security\Nettoyeur;
use App\Entity\Dutil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\RecupnoteFormType;
use App\Form\ChangepointsFormType;
use App\Form\ChangeevalsFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class RecupNoteController extends AbstractController
{
    #[Route('/admin/notes', name: 'app_recupnote')]
    public function recupNote(Request $request,EntityManagerInterface $entityManager, Dutil $dutil,SessionInterface $session): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        //Récupère le formulaire pour choisir la classe et le domaine
        $user = new Dutil();
        $form = $this->createForm(RecupnoteFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            // Récupération et conversion utile du formulaire $form
            $idDomaineVerif=($form->get('id_domain')->getData())->getId();
            $idDomaineVerif=Nettoyeur::nettoyeurInt($idDomaineVerif);

            $idClasseVerif=($form->get('classe')->getData())->getId();
            $idClasseVerif=Nettoyeur::nettoyeurInt($idClasseVerif);

            $dutil=$entityManager->getRepository(Dutil::class)->findAll();

            //Utile pour classifier en changepointsform
            $session = $request->getSession(); 
            $session->set('id_domain', $idDomaineVerif);
            $session->set('id_classe', $idClasseVerif);
            
           
        }else{$idClasseVerif=1;$idDomaineVerif=$idClasseVerif=$points=0;}

        // création du $form_points par utilisation de données en session. 
        $idClasseVerif = Nettoyeur::nettoyeurInt($session->get('id_classe'));
        $idDomaineVerif = Nettoyeur::nettoyeurInt($session->get('id_domain'));
        $form_points = $this->createForm(ChangepointsFormType::class, $user,array('id_classe'=>$idClasseVerif,'id_domain'=>$idDomaineVerif));
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
           
        return $this->render('admin/recup_note.html.twig', [
            'RecupnoteFormType' => $form->createView(),
            'ChangepointsFormType' => $form_points->createView(),
            'dutil'=>$dutil,
            'idDomaineVerif'=>htmlspecialchars($idDomaineVerif),
            'idClasseVerif'=>htmlspecialchars($idClasseVerif),
        ]);
        
        
    
    }
    #[Route('/admin/evals', name: 'app_recupeval')]
    public function recupEval(Request $request,EntityManagerInterface $entityManager, Dutil $dutil,SessionInterface $session): Response
    {$this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        //Récupère le formulaire pour choisir la classe et le domaine
        $user = new Dutil();
        $form = $this->createForm(RecupnoteFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            // Récupération et conversion utile du formulaire $form
            $idDomaineVerif=($form->get('id_domain')->getData())->getId();
            $idDomaineVerif=Nettoyeur::nettoyeurInt($idDomaineVerif);

            $idClasseVerif=($form->get('classe')->getData())->getId();
            $idClasseVerif=Nettoyeur::nettoyeurInt($idClasseVerif);

            $dutil=$entityManager->getRepository(Dutil::class)->findAll();

            //Utile pour classifier en changepointsform
            $session = $request->getSession(); 
            $session->set('id_domain', $idDomaineVerif);
            $session->set('id_classe', $idClasseVerif);
            
           
        }else{$idClasseVerif=1;$idDomaineVerif=$idClasseVerif=$points=0;}

        // création du $form_evals par utilisation de données en session. 
        
        $idClasseVerif = Nettoyeur::nettoyeurInt($session->get('id_classe'));
        $idDomaineVerif = Nettoyeur::nettoyeurInt($session->get('id_domain'));
        $form_evals = $this->createForm(ChangeevalsFormType::class, $user,array('id_classe'=>$idClasseVerif,'id_domain'=>$idDomaineVerif));
        $form_evals->handleRequest($request);

        if ($form_evals->isSubmitted() && $form_evals->isValid()) 
        {
            $dutil=$form_evals->get('Nom')->getData(); 
            $variapoint=Nettoyeur::nettoyeurInt($form_evals->get('points')->getData());;          
            $evals=$dutil->getNoteEvalEco();
            $evals=$evals+$variapoint;
            $dutil->setNoteEvalEco($evals);           

            $entityManager->persist($dutil);
            $entityManager->flush();
            
        }
           
        return $this->render('admin/recup_eval.html.twig', [
            'RecupnoteFormType' => $form->createView(),
            'ChangeevalsFormType' => $form_evals->createView(),
            'dutil'=>$dutil,
            'idDomaineVerif'=>htmlspecialchars($idDomaineVerif), 
            'idClasseVerif'=>htmlspecialchars($idClasseVerif),
        ]);
        
        
    
    }

}
