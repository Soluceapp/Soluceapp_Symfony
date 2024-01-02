<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Dutil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ProfilStudentController extends AbstractController
{
    #[Route('/profile_student', name: 'app_profil_student')]
    public function index(Dutil $dutil,EntityManagerInterface $entityManager,Request $request): Response
    {
        //Méthode complète de modification de base (récupération et affectation).
        $pseudo=$request->get('pseudo');//pas besoin d'htmlchars car redéfini ci-dessous.
        if(isset($pseudo))
        {
        $pseudo=htmlspecialchars($request->get('pseudo'));
        $dutil = new Dutil();
        $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());
        $dutil->setPseudo($pseudo);
        $entityManager->persist($dutil);
        $entityManager->flush();
        }
        
        //récupération du niveau de classe pour affichage.


        return $this->render('profile_student/index.html.twig', [
           
        ]);
    }

    #[Route('/profile_student/changepseudo', name: 'app_changepseudo')]
    public function changepseudo(): Response
    {
        return $this->render('profile_student/changepseudo.html.twig', [
            
        ]);
    }

    #[Route('/profile_student/anciencours', name: 'app_anciencours')]
    public function anciencours(EntityManagerInterface $entityManager): Response
    {


        $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());
        $classe=$dutil->getClasse();


        return $this->render('profile_student/anciencours.html.twig', [
           'classe'=>$classe
        ]);
    }


}
