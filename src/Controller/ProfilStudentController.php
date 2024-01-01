<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Dutil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ProfilStudentController extends AbstractController
{
    #[Route('/profile_student', name: 'app_profil_student')]
    public function index(Dutil $dutil,EntityManagerInterface $entityManager,Request $request): Response
    {
        //Méthode complète de modification de base (récupération et affectation).
        $pseudo=$request->get('pseudo');
        if(isset($pseudo))
        {
        $dutil = new Dutil();
        $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());
        $pseudo=$request->get('pseudo');
        $dutil->setPseudo($pseudo);
        $entityManager->persist($dutil);
        $entityManager->flush();
        }
        

        return $this->render('profile_student/index.html.twig', [
            'controller_name' => 'ProfilStudentController',
        ]);
    }

    #[Route('/profile_student/changepseudo', name: 'app_changepseudo')]
    public function changepseudo(): Response
    {
        return $this->render('profile_student/changepseudo.html.twig', [
            'controller_name' => 'ProfilStudentController',
        ]);
    }

    #[Route('/profile_student/anciencours', name: 'app_anciencours')]
    public function anciencours(): Response
    {
        return $this->render('profile_student/anciencours.html.twig', [
            'controller_name' => 'ProfilStudentController',
        ]);
    }


}
