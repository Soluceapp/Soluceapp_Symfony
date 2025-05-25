<?php

namespace App\Controller\Admin;

use App\Entity\ClassStudent;
use App\Entity\DomaineStudent;
use App\Entity\Dutil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ReinitiaFormType;
use Symfony\Component\HttpFoundation\Request;
use App\Security\Nettoyeur;
class ReinitiaController extends AbstractController
{
    #[Route('/admin/initia', name: 'app_reinitia')]
    public function recupnote(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
    
        $user = new Dutil();
        $form = $this->createForm(ReinitiaFormType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $context = Nettoyeur::nettoyeurStr($form->get('context')->getData()); // Récupération du contexte
            $Iddomaine = $form->get('id_domain')->getData();
            $Idclasse = $form->get('classe')->getData();
    
            $domaine = $entityManager->getRepository(DomaineStudent::class)->find($Iddomaine->getId()); 
            $classe = $entityManager->getRepository(ClassStudent::class)->find($Idclasse->getId());
    
            $users = $entityManager->getRepository(Dutil::class)->findBy([
                'id_domain' => $domaine,
                'classe' => $classe,
            ]);
    
            switch ($context) {
                case 'eco':
                    foreach ($users as $user) {
                        $user->setNoteEvalEco(0);
                        $entityManager->persist($user);
                    }
                    $this->addFlash('success', "Classe réinitialisée (Économie).");
                    break;
    
                case 'gestion':
                    foreach ($users as $user) {
                        $user->setNoteEvalGestion(0);
                        $entityManager->persist($user);
                    }
                    $this->addFlash('success', "Classe réinitialisée (Gestion).");
                    break;

                case 'outilgestion':
                        foreach ($users as $user) {
                            $user->setNoteEvalOutilGestion(0);
                            $entityManager->persist($user);
                        }
                        $this->addFlash('success', "Classe réinitialisée (Outil de Gestion).");
                        break;
    
                default:
                    foreach ($users as $user) {
                        $user->setPoints(0);
                        $user->setNote(0);
                        $entityManager->persist($user);
                    }
                    $this->addFlash('success', "Classe réinitialisée (Participation).");
                    break;
            }
    
            $entityManager->flush();
        }
    
        return $this->render('admin/reinitia.html.twig', [
            'ReinitiaFormType' => $form->createView(),
        ]);
    }
    

        
    
    }



