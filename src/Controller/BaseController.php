<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    #[Route('/base', name: 'app_base')]
    public function index(): Response {return $this->render('base/index.html.twig', ['controller_name' => 'BaseController',]);}
 
    #[Route('/', name: 'home')]
    public function home(){return $this->render('base/accueil.html.twig');}


}
