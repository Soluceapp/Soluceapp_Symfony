<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChevauxController extends AbstractController
{
    #[Route('/activities/chevaux', name: 'app_cheveaux')]
    public function chevaux(): Response
    { $this->denyAccessUnlessGranted('IS_AUTHENTICATED');



        return $this->render('activities/chevaux.html.twig', [
           
        ]);
    }
}
