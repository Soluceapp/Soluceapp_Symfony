<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

//
// Méthodes relatives à l'affichage de la page d'accueil
//
class BaseController extends AbstractController
{ 
   
    #[Route('/', name: 'home')]
    public function home(){return $this->render('base/index.html.twig');}
 
}
