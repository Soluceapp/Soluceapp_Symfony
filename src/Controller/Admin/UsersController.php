<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/utilisateurs', name: 'admin_users_')]
class UsersController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    { $this->denyAccessUnlessGranted('IS_AUTHENTICATED','ROLE_ADMIN');
        return $this->render('admin/users/index.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }
}
