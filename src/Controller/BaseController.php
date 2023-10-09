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

    #[Route('/menux', name: 'route_menux')]
    public function menu1(){return $this->render('base/menu1.html.twig');}

    #[Route('/menuy', name: 'route_menuy')]
    public function menu2(){return $this->render('base/menu2.html.twig');}
}
