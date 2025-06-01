<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\BaseService;

//
// Méthodes relatives à l'affichage de la page d'accueil
//
class BaseController extends AbstractController
{ 

    // Page de connexion de l'application
    #[Route('/', name: 'home')]
    public function home(){return $this->render('base/index.html.twig');}

    // Partie de page pour affichage de menu haut
    #[Route('/menu_x', name: 'route_menu_x')]
    public function menu1(){return $this->render('partials/_header.html.twig',
    ['app_compta'=> 'app_compta']);}

    // Partie de page pour affichage de menu en bas
    #[Route('/menu_y', name: 'route_menu_y')]
    public function menu2(){return $this->render('partials/_footer.html.twig');}
   
}
