<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ActivitiesController extends AbstractController
{
    #[Route('/activities', name: 'app_activities')]
    public function index(): Response
    { 
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        return $this->render('activities/index.html.twig');
    }

    #[Route('/activities/comptafacil', name: 'app_compta')]
    public function comptafacil(): Response
    { 
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        return $this->render('activities/comptafacil.html.twig');
    }


}
