<?php

namespace App\Controller\Admin;

use App\Entity\Activity;
use App\Entity\ClassStudent;
use App\Entity\Dutil;
use App\Entity\Student;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Controller\ActivitiesController;

class AdminConsoleController extends AbstractDashboardController
{

    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN')]
    //#[IsGranted('ROLE_SUPERADMIN')]
    public function index(): Response
    {$this->denyAccessUnlessGranted('IS_AUTHENTICATED');

         return $this->render('admin/dashboard.html.twig');    
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Soluceapp Symfony')
            ->setFaviconPath('images/favicon.ico')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Accueil administration', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', Dutil::class);
        yield MenuItem::linkToCrud('Etudiants', 'fas fa-graduation-cap', Student::class);
        yield MenuItem::linkToCrud('Classes', 'fas fa-people-group', ClassStudent::class);
        yield MenuItem::linkToCrud('Activités', 'fas fa-person-running', Activity::class);
        yield MenuItem::linkToRoute('Tester les activités', 'fa fa-door-open', 'app_activities');
        yield MenuItem::linkToLogout('Se déconnecter', 'fa fa-sign-out');
    }
}
