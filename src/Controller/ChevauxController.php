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

// Méthodes relatives à l'activité petits chevaux
class ChevauxController extends AbstractController
{
    // Permet de choisir le scénario de petits chevaux
    #[Route('/activities/chevaux', name: 'app_chevaux')]
    public function chevaux(
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

    // Passe les scénarios au template
    return $this->render('activities/chevaux.html.twig', [
        'scenarios' => $scenarios, // Liste des scénarios filtrés
        'classe' => $classe, // Classe de l'utilisateur
    ]);
}

    // Permet d'afficher la liste des questions des petits chevaux
    #[Route('/activities/question', name: 'app_question')]
    public function question(
        Request $request, 
        EntityManagerInterface $entityManager, 
        SessionInterface $session
    ): Response {

    // Vérifie si l'utilisateur est authentifié et récupère l'utilisateur
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
    $user = $this->getUser();

    // Récupérer l'ID du scénario depuis le formulaire (récupération nettoyée)
    $idScenario = Nettoyeur::nettoyeurInt(intval(($request->request->get('id_scenario'))));
    if (!$idScenario) {
        $this->addFlash('danger', 'Veuillez sélectionner un scénario.');
        return $this->redirectToRoute('app_chevaux');
    }

    // Charger le scénario correspondant
    $scenario = $entityManager->getRepository(Scenario::class)->find($idScenario);
    if (!$scenario) {
        $this->addFlash('danger', 'Scénario introuvable.');
        return $this->redirectToRoute('app_chevaux');
    }

    //Récupération de la variable scenarioFait (array de 1 à 100) enregistrée précédement pour comparaison
    $dutil = $entityManager->getRepository(Dutil::class)->find($this->getUser());
    $scenarioFait=$dutil->getScenarioFait();

    // Comparaison entre idScenario et scenariofait mémorisé (variable intermédiaire indexScenario)
    if(!$idScenario==""&&$idScenario>0&&$idScenario<100){$indexScenario=$scenarioFait[$idScenario];}
    else
    {
        $this->addFlash('info','Scénario introuvable.');
        $session->clear();
        return $this->redirectToRoute('app_chevaux');
    }
    if($indexScenario==1)
    {
        $this->addFlash('success','Petits chevaux déjà réalisé. Un point a déjà été donné.');
        return $this->redirectToRoute('app_chevaux');
    }

    // Mise en session du scénario sélectionné
    $session->set('id_scenario', $idScenario);

    // Récupération du lien learningapp et affichage tiré de la base
    $lienChevaux=$scenario->getLienChevaux();

    // Redirection vers la vue des questions
    return $this->render('activities/chevaux_2.html.twig', [
        'scenario' => $scenario,
        'lienChevaux'=>$lienChevaux,
        ]);
    }
    
}


