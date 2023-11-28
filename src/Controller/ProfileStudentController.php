<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profil', name: 'profile_')]
class ProfileStudentController extends AbstractController
{
    #[Route('/', name: 'app_profile_student')]
    public function index(): Response
    {
        return $this->render('profile_student/index.html.twig', [
            'controller_name' => 'Profil de l\'utilisateur',
        ]);
    }

    #[Route('/activites', name: 'activities')]
    public function activite(): Response
    {
        return $this->render('profile_student/index.html.twig', [
            'controller_name' => 'Activités de l\'utilisateur',
        ]);
    }
}
