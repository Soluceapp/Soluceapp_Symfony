<?php

namespace App\Controller;

use App\Services\FlashCardService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Security\Nettoyeur;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\FlashCardEco;
use App\Entity\Dutil;
use App\Entity\FlashCardGestion;
use App\Entity\FlashCardOutilGestion;

class EvalFlashCardController extends AbstractController
{
    private $flashCardService;

    public function __construct(FlashCardService $flashCardService)
    {
        $this->flashCardService = $flashCardService;
    }

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
