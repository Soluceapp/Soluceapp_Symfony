<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{ 
   
    #[Route('/', name: 'home')]
    public function home(){return $this->render('base/index.html.twig');}
 
}
