<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Scenario;
use App\Entity\Dutil;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Security\Nettoyeur;

//
// Méthodes relatives à l'activité de mot croisé
//
class MotCroiseController extends AbstractController
{

    // Permet de choisir le scénario des mots croisés
    #[Route('/activities/mot_croise', name: 'app_mot_croise')]
    public function index(
        EntityManagerInterface $entityManager): Response
    {    
        // Vérifie si l'utilisateur est authentifié
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        // Récupère l'utilisateur connecté
        $dutil = $entityManager->getRepository(Dutil::class)->find($this->getUser());

        // Récupère la classe de l'utilisateur
        $classe = $dutil->getClasse();

        // Récupère les scénarios associés à la classe
        $scenarios = $entityManager->getRepository(Scenario::class)->findBy(['classe' => $classe]);
    
        return $this->render('activities/mot_croise.html.twig', [
            'scenarios' => $scenarios, // Liste des scénarios filtrés
            'classe' => $classe, // Classe de l'utilisateur (première, seconde...)
       
        ]);
    }

    // Permet de faire le mots croisés
    #[Route('/activities/mot_croise/mot_croise', name: 'app_mot_croise_2')]
    public function motCroise2(
        Dutil $dutil,
        SessionInterface $session,
        EntityManagerInterface $entityManager,
        Request $request): Response
    {
        
    // Vérifie si l'utilisateur est authentifié et récupère l'utilisateur
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

    // Récupérer l'ID du scénario depuis le formulaire et nettoyage
    $idScenario = Nettoyeur::nettoyeurInt(intval(($request->request->get('id_scenario'))));
    if (!$idScenario) {
        $this->addFlash('danger', 'Veuillez sélectionner un scénario.');
        return $this->redirectToRoute('app_chevaux');
    }
      
    // Charger le scénario correspondant
    $scenario = $entityManager->getRepository(Scenario::class)->find($idScenario);
    if (!$scenario) {
        $this->addFlash('danger', 'Scénario introuvable.');
        return $this->redirectToRoute('app_motcroise');
    }
         
    // Récupération //mise en session de l'id pour récupération en résultat.de la variable motCroiseFait (array de 1 à 100) enregistrée précédement pour comparaison
    $dutil = $entityManager->getRepository(Dutil::class)->find($this->getUser());
    $motCroiseFait=$dutil->getMotCroiseFait();
    
    // Comparaison entre idScenario et motCroiseFait mémorisé (variable intermédiaire indexScenario)
    if(!$idScenario==""&&$idScenario>0&&$idScenario<100){$indexScenario=$motCroiseFait[$idScenario];}
        else
        {
            $this->addFlash('info','Il faut indiquer un numéro de scénario.');
            return $this->redirectToRoute('app_mot_croise');  
        }
    if($indexScenario==1)
        {
            $this->addFlash('success','Mot croisé déjà réalisé.');
            return $this->redirectToRoute('app_mot_croise');  
        }  
    
    // Mise en session de l'id pour récupération en résultat.
    $session->set("id_scenario",$idScenario);

    // Récupération d'information à afficher partir de l'id nettoyée et de la base.
    if(isset($scenario))
        {
        $nameScenario=$scenario->getNameScenario();
        $lienImage=$scenario->getLienImage();
        $lienMotCroise=$scenario->getLienMotCroise();
        $reponseMotCroise=$scenario->getReponseMotCroise();
        }
    else {$this->addFlash('success','Mots croisés déjà réalisé ou impossible.');return $this->redirectToRoute('app_activities');}

    return $this->render('activities/mot_croise_2.html.twig', [
       
            'nameScenario'=>$nameScenario,
            'lienMotCroise'=>$lienMotCroise,
            'reponseMotCroise'=>$reponseMotCroise,
        ]);
    }
}
