<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PartialsController extends AbstractController
{
   
    #[Route('/menux', name: 'route_menux')]
    public function menu1(){return $this->render('partials/_header.html.twig',
    ['app_compta'=> 'app_compta']);
    
    }

    #[Route('/menuy', name: 'route_menuy')]
    public function menu2(){return $this->render('partials/_footer.html.twig');}
}
