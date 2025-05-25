<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ClasseSelectionType;
use App\Repository\FlashCardEcoRepository;
use App\Repository\FlashCardGestionRepository;
use App\Repository\FlashCardOutilGestionRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Security\Nettoyeur;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\FlashCardEco;
use App\Entity\Dutil;
use App\Entity\FlashCardGestion;
use App\Entity\FlashCardOutilGestion;
use App\Services\FlashCardService;

class FlashCardController extends AbstractController
{

   // Constructeur du service du controller
   private $flashCardService;
   public function __construct(FlashCardService $flashCardService)
   {
       $this->flashCardService=$flashCardService;
   }



    // Accueil : Acceuil la réponse du header et demande niveau de classe de l'élève
    #[Route('/flash/card', name: 'app_flashcard')]
    public function index(
        Request $request,
        SessionInterface $session, 
        FlashCardEcoRepository $flashCardEcoRepository,
    ): Response {
        // Récupérer le contexte depuis l'URL et le néttoie ("eco" ou "gestion").
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $context = Nettoyeur::nettoyeurStr($request->query->get('context', 'default'));
        
        // Création du formulaire du choix de niveau
        $form = $this->createForm(ClasseSelectionType::class);
        $form->handleRequest($request);

        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $classeChoisie = $form->get('classe')->getData();
    
            // Rediriger en fonction du contexte
            if ($context === "eco") {

                // Envoie du type de flashcard et de son type de classe
                return $this->redirectToRoute('app_flashcard_eco', [
                        'id' => $classeChoisie->getId(),
                        'choixCard' => $context,
                    ]);
            }
            if ($context === "gestion") {

                // Envoie du type de flashcard et de son type de classe
                return $this->redirectToRoute('app_flashcard_gestion', [
                    'id' => $classeChoisie->getId(),
                    'choixCard' => $context,
                ]);
            }
            if ($context === "outilgestion") {

                // Envoie du type de flashcard et de son type de classe
                return $this->redirectToRoute('app_flashcard_outil_gestion', [
                    'id' => $classeChoisie->getId(),
                    'choixCard' => $context,
                ]);
            }
        }
    
        // Vide la session de randomCards (en cas de callback)
        $session->set('randomCards', '');
        $session->set('randomCard', '');

        // Nécessaire pour utiliser le même template (évite erreur)
        $resultatFlash = Null;

        // Nécessaire pour vider la variable countflashcorrect pour la suite
        if (empty($countFlashCorrect)) {$countFlashCorrect = 0;}
        $session->set('countflashcorrect', $countFlashCorrect);

        // Rendre le template avec les données nécessaires
        return $this->render('activities/flashcard.html.twig', [
            'ClasseSelectionType' => $form->createView(),
            'choixCard' => htmlspecialchars($context),
            'randomCard' => Null,
            'resultatFlash' => $resultatFlash,
        ]);
    }
    
    // Crée activité flashcard eco
    #[Route('/flashcard/eco', name: 'app_flashcard_eco')]
    public function flashcardeco(
        Request $request,
        FlashCardEcoRepository $flashCardEcoRepository,
        SessionInterface $session): Response
    {
        // Récupérer l'id de la classe choisie si elle exite (spécial 1 template)
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $id = intval($request->query->get('id', ''));
        if(!empty($id)) {$session->set('idclasse', $id);}
        $idClasse = Nettoyeur::nettoyeurInt(intval($session->get('idclasse')));

        // Récupérer une flashcard aléatoire néttoyée pour cette classe (vérifie si en session)
        if (!$idClasse) {  return $this->redirectToRoute('app_flashcard'); }
        $randomCards = Nettoyeur::cleanCardEco($session->get('randomCards', Null));
        if (!$randomCards) { $randomCards = $flashCardEcoRepository->findAllByClassId($idClasse);}

        // Premier passage : reset : récupère premier élément, array_slice : garde le reste + mise en session (gestion assynchrone)
        $randomCard = reset($randomCards); 
        if(!$randomCard) {return $this->redirectToRoute('home');} 
        $randomCards = array_slice($randomCards, 1);
        
        // Récupère le résultat par les boutons correct et à revoir. Nettoyeur::nettoyeur_str évite injection (utilise un int).
        $resultatFlash = intval(Nettoyeur::nettoyeurStr($request->query->get('resultatflash', '')));

        // In(dé)crémente la variable countflashcorrect en fonction de resultatflash (note mini 8/20)
        if (empty($countFlashCorrect)) {$countFlashCorrect = 0;}
        $countFlashCorrect = intval(Nettoyeur::nettoyeurStr($session->get('countflashcorrect',0)));       
        switch($resultatFlash){
            case Null : 
                // Traitement du premier affichage
                $resultatFlash="";
                $countFlashCorrect="";
                $randomCardModif = $randomCard;
                $session->set('randomCards', $randomCards);
                $session->set('randomCard', $randomCard);
                break;
            case "1": // "correct"
                // Traitement du décompte de points
                if($countFlashCorrect<8){$countFlashCorrect=8;}
                $countFlashCorrect++;
                if($countFlashCorrect>20){$countFlashCorrect=20;}
                $session->set('countflashcorrect', $countFlashCorrect);
                // Traitement de l'affichage et algorythme profond des Flashcards
                $randomCards = $session->get('randomCards', Null);
                $randomCardModif = reset($randomCards);
                $randomCards = array_slice($randomCards, 1);
                $session->set('randomCards', $randomCards);
                $session->set('randomCard', $randomCardModif);
                if (!$randomCardModif) {$this->addFlash('success', 'Félicitation ! Révision terminée.');
                    return $this->redirectToRoute('app_flashcard');}
                break;
                case "2": // "À revoir"
                    // Le montant minimum du score d'entraînement est 8.
                    if ($countFlashCorrect < 8) {
                        $countFlashCorrect = 8;
                    }
                    $session->set('countflashcorrect', $countFlashCorrect);
                
                    // Récupérer la liste actuelle des cartes en session
                    $randomCards = $session->get('randomCards', []);
                    $randomCard = $session->get('randomCard', []);
                
                    if (!$randomCard) {
                        $this->addFlash('success', 'Félicitations ! Révision terminée.');
                        return $this->redirectToRoute('app_flashcard');
                    }
                
                    // S'assurer que $randomCards est bien un tableau
                    if (!is_array($randomCards)) {
                        $randomCards = [];
                    }
                
                    // Insérer $randomCard en deuxième position
                    $randomCards = array_merge(
                        array_slice($randomCards, 1, 1), 
                        [$randomCard],                   
                        array_slice($randomCards, 2)     
                    );
                    
                
                    // Mettre à jour la session
                    $session->set('randomCards', $randomCards);
                    $randomCardModif = reset($randomCards);
                    $session->set('randomCard', $randomCardModif); // Garder le premier élément en affichage
                    break;
                            default:
                echo "";}

        return $this->render('activities/flashcard.html.twig', [
            'randomCard' => $randomCardModif,
            'choixCard' => 'eco',
            'ClasseSelectionType' => Null,
            'resultatFlash' => htmlspecialchars($countFlashCorrect),
        ]);
    }
    
    // Crée activité flashcard gestion
    #[Route('/flashcard/gestion', name: 'app_flashcard_gestion')]
    public function flashcardgestion(
        Request $request,
        FlashCardGestionRepository $flashCardGestionRepository,
        SessionInterface $session): Response
    {
    // Récupérer l'id de la classe choisie si elle exite (spécial 1 template)
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
    $id = intval($request->query->get('id', ''));
    if(!empty($id)) {$session->set('idclasse', $id);}
    $idClasse = Nettoyeur::nettoyeurInt(intval($session->get('idclasse')));

    // Récupérer une flashcard aléatoire néttoyée pour cette classe (vérifie si en session)
    if (!$idClasse) {  return $this->redirectToRoute('app_flashcard'); }
    $randomCards = Nettoyeur::cleanCardGestion($session->get('randomCards', Null));
    if (!$randomCards) { $randomCards = $flashCardGestionRepository->findAllByClassId($idClasse);}

    // Premier passage : reset : récupère premier élément, array_slice : garde le reste + mise en session (gestion assynchrone)
    $randomCard = reset($randomCards); 
    if(!$randomCard) {return $this->redirectToRoute('home');} 
    $randomCards = array_slice($randomCards, 1);
    
    // Récupère le résultat par les boutons correct et à revoir. Nettoyeur::nettoyeur_str évite injection (utilise un int).
    $resultatFlash = intval(Nettoyeur::nettoyeurStr($request->query->get('resultatflash', '')));

    // In(dé)crémente la variable countflashcorrect en fonction de resultatflash (note mini 8/20)
    if (empty($countFlashCorrect)) {$countFlashCorrect = 0;}
    $countFlashCorrect = intval(Nettoyeur::nettoyeurStr($session->get('countflashcorrect',0)));       
    switch($resultatFlash){
        case Null : 
            // Traitement du premier affichage
            $resultatFlash="";
            $countFlashCorrect="";
            $randomCardModif = $randomCard;
            $session->set('randomCards', $randomCards);
            $session->set('randomCard', $randomCard);
            break;
        case "1": // "correct"
            // Traitement du décompte de points
            if($countFlashCorrect<8){$countFlashCorrect=8;}
            $countFlashCorrect++;
            if($countFlashCorrect>20){$countFlashCorrect=20;}
            $session->set('countflashcorrect', $countFlashCorrect);
            // Traitement de l'affichage et algorythme profond des Flashcards
            $randomCards = $session->get('randomCards', Null);
            $randomCardModif = reset($randomCards);
            $randomCards = array_slice($randomCards, 1);
            $session->set('randomCards', $randomCards);
            $session->set('randomCard', $randomCardModif);
            if (!$randomCardModif) {$this->addFlash('success', 'Félicitation ! Révision terminée.');
                return $this->redirectToRoute('app_flashcard');}
            break;
            case "2": // "À revoir"
                // Le montant minimum du score d'entraînement est 8.
                if ($countFlashCorrect < 8) {
                    $countFlashCorrect = 8;
                }
                $session->set('countflashcorrect', $countFlashCorrect);
            
                // Récupérer la liste actuelle des cartes en session
                $randomCards = $session->get('randomCards', []);
                $randomCard = $session->get('randomCard', []);
            
                if (!$randomCard) {
                    $this->addFlash('success', 'Félicitations ! Révision terminée.');
                    return $this->redirectToRoute('app_flashcard');
                }
            
                // S'assurer que $randomCards est bien un tableau
                if (!is_array($randomCards)) {
                    $randomCards = [];
                }
            
                // Insérer $randomCard en deuxième position
                $randomCards = array_merge(
                    array_slice($randomCards, 1, 1), 
                    [$randomCard],                   
                    array_slice($randomCards, 2)     
                );
                
            
                // Mettre à jour la session
                $session->set('randomCards', $randomCards);
                $randomCardModif = reset($randomCards);
                $session->set('randomCard', $randomCardModif); // Garder le premier élément en affichage
                break;
                        default:
            echo "";}

        return $this->render('activities/flashcard.html.twig', [
            'randomCard' => $randomCard,
            'choixCard' => 'gestion',
            'ClasseSelectionType' => Null,
            'resultatFlash' => htmlspecialchars($countFlashCorrect),
        ]);
    }

// Crée activité flashcard outil de gestion
#[Route('/flashcard/outil_gestion', name: 'app_flashcard_outil_gestion')]
public function flashcardoutilgestion(
    Request $request,
    FlashCardOutilGestionRepository $flashCardOutilGestionRepository,
    SessionInterface $session): Response
{
    // Récupérer l'id de la classe choisie si elle exite (spécial 1 template)
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
    $id = intval($request->query->get('id', ''));
    if(!empty($id)) {$session->set('idclasse', $id);}
    $idClasse = Nettoyeur::nettoyeurInt(intval($session->get('idclasse')));

    // Récupérer une flashcard aléatoire néttoyée pour cette classe (vérifie si en session)
    if (!$idClasse) {  return $this->redirectToRoute('app_flashcard'); }
    $randomCards = Nettoyeur::cleanCardOutilGestion($session->get('randomCards', Null));
    if (!$randomCards) { $randomCards = $flashCardOutilGestionRepository->findAllByClassId($idClasse);}

    // Premier passage : reset : récupère premier élément, array_slice : garde le reste + mise en session (gestion assynchrone)
    $randomCard = reset($randomCards); 
    if(!$randomCard) {return $this->redirectToRoute('home');} 
    $randomCards = array_slice($randomCards, 1);
    
    // Récupère le résultat par les boutons correct et à revoir. Nettoyeur::nettoyeur_str évite injection (utilise un int).
    $resultatFlash = intval(Nettoyeur::nettoyeurStr($request->query->get('resultatflash', '')));

    // In(dé)crémente la variable countflashcorrect en fonction de resultatflash (note mini 8/20)
    if (empty($countFlashCorrect)) {$countFlashCorrect = 0;}
    $countFlashCorrect = intval(Nettoyeur::nettoyeurStr($session->get('countflashcorrect',0)));       
    switch($resultatFlash){
        case Null : 
            // Traitement du premier affichage
            $resultatFlash="";
            $countFlashCorrect="";
            $randomCardModif = $randomCard;
            $session->set('randomCards', $randomCards);
            $session->set('randomCard', $randomCard);
            break;
        case "1": // "correct"
            // Traitement du décompte de points
            if($countFlashCorrect<8){$countFlashCorrect=8;}
            $countFlashCorrect++;
            if($countFlashCorrect>20){$countFlashCorrect=20;}
            $session->set('countflashcorrect', $countFlashCorrect);
            // Traitement de l'affichage et algorythme profond des Flashcards
            $randomCards = $session->get('randomCards', Null);
            $randomCardModif = reset($randomCards);
            $randomCards = array_slice($randomCards, 1);
            $session->set('randomCards', $randomCards);
            $session->set('randomCard', $randomCardModif);
            if (!$randomCardModif) {$this->addFlash('success', 'Félicitation ! Révision terminée.');
                return $this->redirectToRoute('app_flashcard');}
            break;
            case "2": // "À revoir"
                // Le montant minimum du score d'entraînement est 8.
                if ($countFlashCorrect < 8) {
                    $countFlashCorrect = 8;
                }
                $session->set('countflashcorrect', $countFlashCorrect);
            
                // Récupérer la liste actuelle des cartes en session
                $randomCards = $session->get('randomCards', []);
                $randomCard = $session->get('randomCard', []);
            
                if (!$randomCard) {
                    $this->addFlash('success', 'Félicitations ! Révision terminée.');
                    return $this->redirectToRoute('app_flashcard');
                }
            
                // S'assurer que $randomCards est bien un tableau
                if (!is_array($randomCards)) {
                    $randomCards = [];
                }
            
                // Insérer $randomCard en deuxième position
                $randomCards = array_merge(
                    array_slice($randomCards, 1, 1), 
                    [$randomCard],                   
                    array_slice($randomCards, 2)     
                );
                
            
                // Mettre à jour la session
                $session->set('randomCards', $randomCards);
                $randomCardModif = reset($randomCards);
                $session->set('randomCard', $randomCardModif); // Garder le premier élément en affichage
                break;
                        default:
            echo "";}

    return $this->render('activities/flashcard.html.twig', [
        'randomCard' => $randomCard,
        'choixCard' => 'outilgestion',
        'ClasseSelectionType' => Null,
        'resultatFlash' => htmlspecialchars($countFlashCorrect),
    ]);
}

// Evaluation des flashcards


#[Route('activities/eval_flashcard', name: 'app_eval_flashcard')]
public function evalFlashCard(
    Request $request,
    FlashCardService $flashCardService,
    SessionInterface $session, 
): Response {
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
    // Bouton eco ou gestion -> $context = "eco" ou "gestion"
    $context = Nettoyeur::nettoyeurStr($request->query->get('context', 'outilgestion'));

    // Récupére $user -> données colonne de l'utilisateur 
    $user = $this->getUser();

    // Récupère uniquement id des flashcard correspondant à classe de l'utilisateur
    // Sépare éco et gestion, donc récupère les id des Flashcard à montrer !
    $flashcardId = [];
    $flashcardId = $flashCardService->getIdFlashCardsByUserAndClass($user, $context);
    
    // Les id à montrer sont mélangées et limitées à la quantité de questions désirées.
    $flashcardId = FlashCardService::melangerEtLimiter($flashcardId);

    // Mise en session des id des flashcards à utiliser et du context (car besoin récurrent).
    $session->set('flashcardId', $flashcardId);
    $session->set('context', $context);
    $session->set('countPassage', 0);

    
    return $this->render('activities/eval_flashcard.html.twig', [
        'choixcard' => htmlspecialchars($context),
    ]);
}

#[Route('activities/eval_flashcard_detail', name: 'app_eval_flashcard_detail')]
public function evalFlashCardDetail(
Request $request,
FlashCardService $flashCardService,
SessionInterface $session,
EntityManagerInterface $em,
Dutil $dutil    
): Response {

$this->denyAccessUnlessGranted('IS_AUTHENTICATED');
// Récupération du contexte, des IDs de la session et du décompte de question (pour éviter problème affichage)
$context = Nettoyeur::nettoyeurStr($session->get('context', null));
$flashcardId = $session->get('flashcardId', []);
$countPassage = Nettoyeur::nettoyeurInt($session->get('countPassage', null));
$user = $this->getUser();

// Vérification du contexte et des questions disponibles
if (!$context || empty($flashcardId)) {return $this->redirectToRoute('app_profil_student');}

// Charger la question en fonction de $countPassage
$currentQuestionIndex = $session->get('countPassage', 0);
if (!isset($flashcardId[$currentQuestionIndex])) {
    $this->addFlash('success','Evaluation terminée.');
    return $this->redirectToRoute('app_profil_student');}

// Récupérer l'ID de la question actuelle
$currentQuestionId = $flashcardId[$currentQuestionIndex]['id'] ?? null;
if (!$currentQuestionId) {throw new \Exception('No valid question ID found.');}

// Charger la question depuis la base de données
$question = match ($context) {
    'eco' => $em->getRepository(FlashCardEco::class)->find($currentQuestionId),
    'gestion' => $em->getRepository(FlashCardGestion::class)->find($currentQuestionId),
    'outilgestion' => $em->getRepository(FlashCardOutilGestion::class)->find($currentQuestionId),
    default => null,};

if (!$question) {throw new \Exception('Question not found in database.');}

// Si le formulaire est soumis
if ($request->isMethod('POST')) 
{
    $userAnswer = Nettoyeur::nettoyeurStr($request->request->get('answer'));
    if($context === 'eco') {$correctAnswer = $question->getVersoEco();} 
    elseif($context === 'gestion') {$correctAnswer = $question->getVersoGestion();} 
    elseif($context === 'outilgestion') {$correctAnswer = $question->getVersoOutilGestion();}        

    // Normaliser et comparer les réponses
    $userAnswer = strtolower(trim(FlashCardService::enleverAccents($userAnswer)));
    $correctAnswer = strtolower(trim(FlashCardService::enleverAccents($correctAnswer)));
  
    $isCorrect = ($correctAnswer === FlashCardService::verifierSousChaine($correctAnswer,$userAnswer));
    if ($isCorrect) {
        if($context=='eco')
        {$noteEval = Nettoyeur::nettoyeurInt($user->getNoteEvalEco()) + 1;
            if($noteEval<8  ){$noteEval=9;}
            if($noteEval>20  ){$noteEval=20;}
            $user->setNoteEvalEco($noteEval);}
        elseif($context=='gestion')
        {$noteEval = Nettoyeur::nettoyeurInt($user->getNoteEvalGestion()) + 1;
            if($noteEval<8 ){$noteEval=9;}
            if($noteEval>20  ){$noteEval=20;}
            $user->setNoteEvalGestion($noteEval);}
        elseif($context=='outilgestion')
        {$noteEval = Nettoyeur::nettoyeurInt($user->getNoteEvalOutilGestion()) + 1;
            if($noteEval<8 ){$noteEval=9;}
            if($noteEval>20  ){$noteEval=20;}
            $user->setNoteEvalOutilGestion($noteEval);}
        $em->persist($user);
        $em->flush();
    }

    // Passer à la question suivante
    $session->set('countPassage', $currentQuestionIndex + 1);
    return $this->redirectToRoute('app_eval_flashcard_detail');
}
return $this->render('activities/eval_flashcard_detail.html.twig', [
    'context' => $context,
    'question' => $question,
    'countPassage' =>htmlspecialchars($countPassage),
    
]);}


}
