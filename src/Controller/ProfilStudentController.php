<?php

namespace App\Controller;

use App\Entity\Activity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Dutil;
use App\Form\ChangeclasseFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ScenarioRepository;
use App\Security\Nettoyeur;


//
// Méthodes relatives à la gestion du profil de l'élève
//
class ProfilStudentController extends AbstractController
{

    private $scenarioRepository;

    // Injection du repository via le constructeur
    public function __construct(ScenarioRepository $scenarioRepository)
    {
        $this->scenarioRepository = $scenarioRepository;
    }


    #[Route('/profile_student', name: 'app_profil_student')]
    public function index(Dutil $dutil,EntityManagerInterface $entityManager,Request $request): Response
    {$this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        //Méthode complète de modification du pseudo
        $pseudo=$request->get('pseudo');
        if(isset($pseudo))
        {
        $pseudo=Nettoyeur::nettoyeurStr($request->get('pseudo'));
        $dutil = new Dutil();
        $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());
        $dutil->setPseudo($pseudo);
        $entityManager->persist($dutil);
        $entityManager->flush();
        }
        
        return $this->render('profile_student/index.html.twig', [
            
        ]);
    }

    #[Route('/profile_student/change_pseudo', name: 'app_change_pseudo')]
    public function changePseudo(): Response
    {
        return $this->render('profile_student/change_pseudo.html.twig', [
            
        ]);
    }

    #[Route('/profile_student/ancien_cours', name: 'app_ancien_cours')]
    public function ancienCours(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
    
        // Récupération de l'utilisateur connecté et ses colonnes.
        $dutil = $entityManager->getRepository(Dutil::class)->find($this->getUser());
        $scenarioFait = $dutil->getScenarioFait();
        $motCroiseFait = $dutil->getMotCroiseFait();
    
        // Convertir les tableaux en entiers
        foreach ($scenarioFait as $key => $value) {
            $scenarioFait[$key] = (int) $value;
        }
        foreach ($motCroiseFait as $key => $value) {
            $motCroiseFait[$key] = (int) $value;
        }
    
        // Fusion des tableaux
        $gagnesFusionnes = [];
        foreach ($scenarioFait as $key => $value) {
            $gagnesFusionnes[$key] = (int) $value + (int) ($motCroiseFait[$key] ?? 0);
            if ($gagnesFusionnes[$key] > 1) {
                $gagnesFusionnes[$key] = 1; // Toujours 0 ou 1
            }
        }

        foreach ($gagnesFusionnes as $index => $value) {
            if ($value === 1) {
                // Récupérer l'entité Scenario par son ID (index)
                $scenario = $this->scenarioRepository->find($index);
                if ($scenario) {
                    // Ajouter le lien de l'image à la liste
                    $scenariosGagnes[] = $scenario->getLienImage();
                }
            }
        }
          
        // On passe uniquement les scénarios gagnés au template
        return $this->render('profile_student/ancien_cours.html.twig', [
            'scenariosGagnes' => $scenariosGagnes,
        ]);
    }
    
    #[Route('/profile_student/change_classe', name: 'app_change_classe')]
    public function changeClasse(Request $request,EntityManagerInterface $entityManager, Dutil $dutil): Response
    {$this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());      
        $form = $this->createForm(ChangeclasseFormType::class, $dutil);
        $form->handleRequest($request);


       if ($form->isSubmitted() && $form->isValid()) 
        {
            $entityManager->persist($dutil);
            $entityManager->flush();
            $this->addFlash('success',"Attention. Les scénarios sont spécifiques à l'année.");
            return $this->redirectToRoute('app_profil_student');  
        }
        return $this->render('profile_student/change_classe.html.twig', [
           
            'changeclasseForm' => $form->createView(),
        ]);

    }
    #[Route('/activities/liste', name: 'app_listea')]
    public function listeActivity(Request $request,EntityManagerInterface $entityManager, Dutil $dutil): Response
    {$this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $dutil=$entityManager->getRepository(Activity::class)->find($this->getUser());
        $activity=$dutil->getNameActivity();

        return $this->render('profile_student/change_classe.html.twig', [
           
         'activity'=>$activity
        ]);

    }
}
