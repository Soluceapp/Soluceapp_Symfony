<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//
// Méthodes relatives à une activité non encore mise en place 
//
class ComptafacilController extends AbstractController
{
    #[Route('/activities/comptafacil', name: 'app_compta')]
    public function comptafacil(): Response
    { 
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');


        return $this->render('activities/comptafacil.html.twig');
    }
}
