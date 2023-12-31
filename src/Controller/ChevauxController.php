<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Dutil;


class ChevauxController extends AbstractController
{
    #[Route('/activities/chevaux', name: 'app_chevaux')]
    public function chevaux(EntityManagerInterface $entityManager): Response
    { $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        


        return $this->render('activities/chevaux.html.twig', [
 
        ]);
    }
}
