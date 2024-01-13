<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CoursFormType; 
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Scenario;

class CoursController extends AbstractController
{
    #[Route('/activities/cours', name: 'app_cours')]
    public function cours(Request $request,EntityManagerInterface $entityManager, Scenario $scenario): Response
    {$this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $dutil=$entityManager->getRepository(Scenario::class)->find($this->getUser());      
        $form = $this->createForm(CoursFormType::class, $scenario);
        $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) 
        {
            
           // $entityManager->persist($dutil);
           // $entityManager->flush();
            $this->addFlash('success',"Attention. Les scénarios sont spécifiques à l'année.");
            return $this->redirectToRoute('app_profil_student');  


        }





        return $this->render('activities/cours.html.twig', [
            'CoursFormType' => $form->createView(),
        ]);
    }
}
