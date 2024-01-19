<?php

namespace App\Controller\Admin;

use App\Entity\Activity;
use App\Entity\ClassStudent;
use App\Entity\DomaineStudent;
use App\Entity\Dutil;
use App\Entity\Scenario;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;

class AdminConsoleController extends AbstractDashboardController
{

    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {$this->denyAccessUnlessGranted('ROLE_ADMIN');

         return $this->render('admin/dashboard.html.twig');    
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Soluceapp Symfony')
            ->setFaviconPath('images/favicon.ico')
            ->renderContentMaximized();
    }

    public function SuperAdminRegul( Dutil $dutil,EntityManagerInterface $entityManager): string
    {
        //Méthode de récupération de superadmin
        $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());
        $dutil->getId();
        $roles=$dutil->getRoles();
        $superadmin=$roles[2];
        return $superadmin;
    }

    public function configureMenuItems(): iterable
    {
        
        yield MenuItem::linkToDashboard('Accueil administration', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-graduation-cap', Dutil::class);
        yield MenuItem::linkToCrud('Niveau des élèves', 'fas fa-people-group', ClassStudent::class);
        yield MenuItem::linkToCrud('Classe des élèves', 'fas fa-people-group', DomaineStudent::class);
        yield MenuItem::linkToCrud('Activités', 'fas fa-person-running', Activity::class);
        yield MenuItem::linkToCrud('Scenarios', 'fas fa-person-running', Scenario::class);
        yield MenuItem::linkToRoute('Accès aux activités', 'fa fa-door-open', 'app_activities');
        yield MenuItem::linkToRoute('Editer les notes', 'fa-solid fa-money-bill-trend-up', 'app_recupnote');
        yield MenuItem::linkToRoute('Réinitialiser une classe', 'fa-regular fa-rectangle-xmark', 'app_reinitia');
        yield MenuItem::linkToLogout('Se déconnecter', 'fa fa-sign-out');
    }
}
