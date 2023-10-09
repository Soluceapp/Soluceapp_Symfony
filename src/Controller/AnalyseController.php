<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnalyseController extends AbstractController
{
    #[Route('/analyse', name: 'app_analyse')]
    public function index(): Response {return $this->render('analyse/index.html.twig', ['controller_name' => 'AnalyseController',]);}

    #[Route('/', name: 'home')]
    public function home(){return $this->render('analyse/home.html.twig',['title'=>"Monsieur x", 'age'=>17]);}

    #[Route('/essai', name: 'app_essai')]
    public function essai(){return $this->render('analyse/essai.html.twig');}
}
