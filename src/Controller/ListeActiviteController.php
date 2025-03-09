<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//
// Méthodes relatives à une activité non encore mise en place 
//
class ListeActiviteController extends AbstractController
{
    #[Route('/activities/liste', name: 'app_activite')]
    public function comptafacile(): Response
    { 
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');


        return $this->render('activities/liste_activite.html.twig');
    }
}
