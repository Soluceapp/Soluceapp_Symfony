<?php

namespace App\Controller;

use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Dutil;
use App\Form\ChangeclasseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\ClassStudent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProfilStudentController extends AbstractController
{
    #[Route('/profile_student', name: 'app_profil_student')]
    public function index(Dutil $dutil,EntityManagerInterface $entityManager,Request $request): Response
    {$this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        //Méthode complète de modification du pseudo
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
    {$this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());
        $classe=$dutil->getClasse();
        $scenariofait=$dutil->getScenariofait();
        $motcroisefait=$dutil->getMotcroisefait();

        return $this->render('profile_student/anciencours.html.twig', [
           'classe'=>$classe,
           'scenariofait'=>$scenariofait,
           'motcroisefait'=>$motcroisefait,
        ]);
    }

    #[Route('/profile_student/changeclasse', name: 'app_changeclasse')]
    public function changeclasse(Request $request,EntityManagerInterface $entityManager, Dutil $dutil): Response
    {$this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        //$dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());

      /*  $form =$this->createFormBuilder()
        ->add('classe', EntityType::class,['attr'=> ['class' =>'style26'],'label' =>'Me passer en','class'=>ClassStudent::class, 
        'choice_label'=>function(ClassStudent $classe){return $classe->getId() . ' - ' . $classe->getNameClass();}])
        //->add('pseudo', TextType::class,['attr'=> ['class' =>'style26'],'label' =>'Votre pseudo'])
        ->add('submit', SubmitType::class,['attr'=> ['class' =>'btn btn-primary btn-lg'],'label' =>'Valider'])
        ->getForm()
        ;*/
        
        $form = $this->createForm(ChangeclasseType::class, $dutil);
        $form->handleRequest($request);


       if ($form->isSubmitted() && !$form->isValid()) 
        {
           // $dutil->setClasse($form->get('classe')->getData());
            dd('traitement');
            //$Idclasse=$form->get('classe')->getData();
            //$Nameclasse=htmlspecialchars($Idclasse->getId());
           // $dutil->setClasse($Nameclasse);
            $entityManager->persist($dutil);
            $entityManager->flush();
        }
        return $this->render('profile_student/changeclasse.html.twig', [
           
            'changeclasseForm' => $form->createView(),
        ]);

    }
}
