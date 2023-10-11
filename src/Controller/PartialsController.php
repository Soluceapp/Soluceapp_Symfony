<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PartialsController extends AbstractController
{
    #[Route('/partials', name: 'app_partials')]
    public function index(): Response
    {
        return $this->render('partials/index.html.twig', [
            'controller_name' => 'PartialsController',
        ]);
    }

    #[Route('/menux', name: 'route_menux')]
    public function menu1(){return $this->render('partials/_header.html.twig');}

    #[Route('/menuy', name: 'route_menuy')]
    public function menu2(){return $this->render('partials/_footer.html.twig');}
}
