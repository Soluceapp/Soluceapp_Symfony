<?php

namespace App\Services;

use App\Entity\UserFlashCard;
use App\Repository\UserFlashCardRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Dutil;
use App\Entity\FlashCardEco;
use App\Entity\FlashCardGestion;
use App\Entity\FlashCardOutilGestion;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\FlashCardEcoRepository;
use App\Security\Nettoyeur;
class FlashCardService
{
    private UserFlashCardRepository $userFlashCardRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(UserFlashCardRepository $userFlashCardRepository, EntityManagerInterface $entityManager,private FlashCardEcoRepository $flashCardEcoRepository)
    {
        $this->userFlashCardRepository = $userFlashCardRepository;
        $this->entityManager = $entityManager;
    }

    public function prepareFlashcardActivity(
        Request $request,
        SessionInterface $session,
        callable $cleanCardFn, // ex: [Nettoyeur::class, 'cleanCardEco']
        callable $repositoryFindFn // ex: fn($idClasse) => $repo->findAllByClassId($idClasse)
    ): array {
        $id = intval($request->query->get('id', ''));
        if (!empty($id)) {
            $session->set('idclasse', $id);
        }

        // Récupération sécurisée de la classe choisie pour la révision de flashcard
        $idClasse = Nettoyeur::nettoyeurInt(intval($session->get('idclasse')));

        if (!$idClasse) {
            return ['redirect' => 'app_flashcard'];
        }

        // Récupération des flashcards mis en session (sécurisation à améliorer)
        $randomCards = call_user_func($cleanCardFn, $session->get('randomCards', null));
        if (!$randomCards) {
            $randomCards = call_user_func($repositoryFindFn, $idClasse);
        }

        $randomCard = reset($randomCards);
        if (!$randomCard) {
            return ['redirect' => 'home'];
        }

        // Récupération de l'id du flashcard et de si elle est réussie ou pas ainsi que le décompte de point.
        $randomCards = array_slice($randomCards, 1);
        $resultatFlash = intval(Nettoyeur::nettoyeurStr($request->query->get('resultatflash', '')));
        $countFlashCorrect = intval(Nettoyeur::nettoyeurStr($session->get('countflashcorrect', 0)));

        switch ($resultatFlash) {
            case null:
                $resultatFlash = "";
                $countFlashCorrect = "";
                $randomCardModif = $randomCard;
                $session->set('randomCards', $randomCards);
                $session->set('randomCard', $randomCard);
                break;

            case 1:
                if ($countFlashCorrect < 8) {
                    $countFlashCorrect = 8;
                }
                $countFlashCorrect++;
                $countFlashCorrect = min($countFlashCorrect, 20);

                $session->set('countflashcorrect', $countFlashCorrect);
                $randomCards = $session->get('randomCards', null);
                $randomCardModif = reset($randomCards);
                $randomCards = array_slice($randomCards, 1);
                $session->set('randomCards', $randomCards);
                $session->set('randomCard', $randomCardModif);

                if (!$randomCardModif) {
                    return ['redirect' => 'app_flashcard', 'flash' => 'Félicitation ! Révision terminée.'];
                }
                break;

            case 2:
                if ($countFlashCorrect < 8) {
                    $countFlashCorrect = 8;
                }
                $session->set('countflashcorrect', $countFlashCorrect);
                $randomCards = $session->get('randomCards', []);
                $randomCard = $session->get('randomCard', []);

                if (!$randomCard) {
                    return ['redirect' => 'app_flashcard', 'flash' => 'Félicitations ! Révision terminée.'];
                }

                if (!is_array($randomCards)) {
                    $randomCards = [];
                }

                $randomCards = array_merge(
                    array_slice($randomCards, 1, 1),
                    [$randomCard],
                    array_slice($randomCards, 2)
                );

                $session->set('randomCards', $randomCards);
                $randomCardModif = reset($randomCards);
                $session->set('randomCard', $randomCardModif);
                break;

            default:
                $randomCardModif = $randomCard;
        }

        return [
            'randomCard' => $randomCardModif ?? null,
            'countFlashCorrect' => $countFlashCorrect
        ];
    }

///
// Fonctions utiles pour l'évaluation des flashcards.
///
    public function getIdFlashCardsByUserAndClass(Dutil $user, string $context): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
    
        // Construction de la requête selon le contexte
        if ($context === 'eco') {
            $queryBuilder->select('fc.id')
                ->from(FlashCardEco::class, 'fc')
                ->leftJoin(UserFlashCard::class, 'ufc', 'WITH', 'ufc.flashCardEco = fc.id AND ufc.dutil = :user')
                //->where('ufc.id IS NULL') // Flashcards non encore essayées
                //->andWhere('fc.classe = :class')
                ->Where('fc.classe = :class')
                ->setParameter('user', $user)
                ->setParameter('class', $user->getClasse());
        } elseif ($context === 'gestion') {
            $queryBuilder->select('fc.id')
                ->from(FlashCardGestion::class, 'fc')
                ->leftJoin(UserFlashCard::class, 'ufc', 'WITH', 'ufc.flashCardGestion = fc.id AND ufc.dutil = :user')
                //->where('ufc.id IS NULL') // Flashcards non encore essayées
                //->andWhere('fc.classe = :class')
                -> Where('fc.classe = :class')
                ->setParameter('user', $user)
                ->setParameter('class', $user->getClasse());
        } elseif ($context === 'outilgestion') {
            $queryBuilder->select('fc.id')
                ->from(FlashCardOutilGestion::class, 'fc')
                ->leftJoin(UserFlashCard::class, 'ufc', 'WITH', 'ufc.flashCardOutilGestion = fc.id AND ufc.dutil = :user')
                //->where('ufc.id IS NULL') // Flashcards non encore essayées
                //->andWhere('fc.classe = :class')
                -> Where('fc.classe = :class')
                ->setParameter('user', $user)
                ->setParameter('class', $user->getClasse());
        } else {
            throw new \InvalidArgumentException("Contexte invalide. Utilisez 'eco' 'outilgestion' ou ou 'gestion'.");
        }
    
        // Récupérer les identifiants
        return $queryBuilder->getQuery()->getArrayResult();
    }

    // Pour l'évaluation limitation du nombre de flascard utilisé à 13 (12 visé en controller)
    public static function melangerEtLimiter(array $array): array
    {
        // Vérifier si le tableau contient des éléments
        if (empty($array)) {return [];}
    
        // Mélanger le tableau
        shuffle($array);
        // Retourner les 13 premiers éléments
        return array_slice($array, 0, 13);
    }

    // Pour éviter erreurs élèves.
    public static function enleverAccents(string $texte): string
    {
        return iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texte);
    }

    public static function verifierSousChaine(string $correctAnswer, string $userAnswer): string
    {
        // Vérifie si $A est une sous-chaîne de $B
        if (strpos($userAnswer, $correctAnswer) !== false) {
            return $correctAnswer;
        }
    
        return '';
    }









}
