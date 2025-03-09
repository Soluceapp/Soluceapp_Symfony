<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

//
// Méthodes utiles à l'affectation partielle de données dans un html
//
class PartialsController extends AbstractController
{
   
    #[Route('/menu_x', name: 'route_menu_x')]
    public function menu1(){return $this->render('partials/_header.html.twig',
    ['app_compta'=> 'app_compta']);
    
    }

    #[Route('/menu_y', name: 'route_menu_y')]
    public function menu2(){return $this->render('partials/_footer.html.twig');}
}
