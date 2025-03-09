<?php

namespace App\Services;

use App\Entity\UserFlashCard;
use App\Repository\UserFlashCardRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Dutil;
use App\Entity\FlashCardEco;
use App\Entity\FlashCardGestion;
use App\Entity\FlashCardOutilGestion;
use Symfony\Component\HttpFoundation\Response;
class FlashCardService
{
    private UserFlashCardRepository $userFlashCardRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(UserFlashCardRepository $userFlashCardRepository, EntityManagerInterface $entityManager)
    {
        $this->userFlashCardRepository = $userFlashCardRepository;
        $this->entityManager = $entityManager;
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
    
    public function getNextFlashCardId(Dutil $user, string $context): ?int
    {
        // Récupère les IDs des flashcards non encore tentées
        $flashCardIds = $this->getIdFlashCardsByUserAndClass($user, $context);
    
        // Retourne le premier ID ou null si la liste est vide
        return $flashCardIds[0] ?? null;
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
