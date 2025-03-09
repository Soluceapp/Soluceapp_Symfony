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
    #[Route('/activities/compta_facile', name: 'app_compta')]
    public function comptaFacile(): Response
    { 
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');


        return $this->render('activities/compta_facile.html.twig');
    }
}
