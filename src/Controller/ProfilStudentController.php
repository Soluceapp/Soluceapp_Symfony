<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Dutil;
use App\Form\ChangeclasseFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


class ProfilStudentController extends AbstractController
{
    #[Route('/profile_student', name: 'app_profil_student')]
    public function index(Dutil $dutil,EntityManagerInterface $entityManager,Request $request): Response
    {$this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        //Méthode complète de modification du pseudo
        $pseudo=$request->get('pseudo');//pas besoin d'htmlchars car redéfini ci-dessous.
        if(isset($pseudo))
        {
        $pseudo=htmlspecialchars($request->get('pseudo'));
        $dutil = new Dutil();
        $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());
        $dutil->setPseudo($pseudo);
        $entityManager->persist($dutil);
        $entityManager->flush();
        }
        
        return $this->render('profile_student/index.html.twig', [
            
        ]);
    }

    #[Route('/profile_student/changepseudo', name: 'app_changepseudo')]
    public function changepseudo(): Response
    {
        return $this->render('profile_student/changepseudo.html.twig', [
            
        ]);
    }

    #[Route('/profile_student/anciencours', name: 'app_anciencours')]
    public function anciencours(EntityManagerInterface $entityManager): Response
    {$this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());
        $classe=$dutil->getClasse();
        $scenariofait=$dutil->getScenariofait();
        $motcroisefait=$dutil->getMotcroisefait();

        return $this->render('profile_student/anciencours.html.twig', [
           'classe'=>$classe,
           'scenariofait'=>$scenariofait,
           'motcroisefait'=>$motcroisefait,
        ]);
    }

    #[Route('/profile_student/changeclasse', name: 'app_changeclasse')]
    public function changeclasse(Request $request,EntityManagerInterface $entityManager, Dutil $dutil): Response
    {$this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());      
        $form = $this->createForm(ChangeclasseFormType::class, $dutil);
        $form->handleRequest($request);


       if ($form->isSubmitted() && $form->isValid()) 
        {
            $entityManager->persist($dutil);
            $entityManager->flush();
            $this->addFlash('success',"Attention. Les scénarios sont spécifiques à l'année.");
            return $this->redirectToRoute('app_profil_student');  
        }
        return $this->render('profile_student/changeclasse.html.twig', [
           
            'changeclasseForm' => $form->createView(),
        ]);

    }
}
