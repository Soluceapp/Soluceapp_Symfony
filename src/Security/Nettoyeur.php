<?php

namespace App\Security;
use App\Controller\ResultatController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Nettoyeur extends AbstractController 
{

    public static function nettoyeurStr($variarecup): string
    {
    if (strlen($variarecup) > 255) {$variarecup="";}
    $symboles ='/[^-a-z0-9 \\éèàïîùçê,?.]+/i';
    $variarecup = (string)preg_replace($symboles,'',$variarecup); 
    return $variarecup;
    }
    public static function nettoyeurInt($variarecup): int
    {
    if ($variarecup !== null && strlen($variarecup) > 255) {$variarecup = "";}
    if ($variarecup !== null){$symboles ='/[^0-9\\=-]+/i';
    $variarecup = (int)preg_replace($symboles,'',$variarecup); }
    if (filter_var($variarecup, FILTER_VALIDATE_INT) == false) {$variarecup = 0;}
    return intval($variarecup);
    }

    // Nettoie les array multidimentionnels.
    public static function nettoyeurArray($variarecup): array
    {
    if (!is_array($variarecup)) {return [];}
    $resultat = [];
    foreach ($variarecup as $key => $value) {
        // Vérifier que chaque élément est un tableau et contient une clé 'id'
        if (isset($value['id'])) {
            // Extraire l'ID, qui est un nombre
            $id = $value['id'];

            // Nettoyer l'ID s'il est sous forme de chaîne avec des symboles
            if (is_string($id)) {
                // Nettoyage des symboles non désirés
                $id = preg_replace('/[^0-9]/', '', $id); // Garder seulement les chiffres
            }

            // Ajouter l'ID nettoyé dans le résultat
            $resultat[] = (int) $id;
        }
    }
    return $resultat;}

   // Fonction pour nettoyer une carte
    public static function cleanCardEco($card) {
        if (!is_array($card)) return false;

        $id = isset($card['id']) ? filter_var($card['id'], FILTER_VALIDATE_INT) : null;
        $rectoEco = isset($card['rectoEco']) ? filter_var($card['rectoEco'], FILTER_SANITIZE_STRING) : null;
        $versoEco = isset($card['versoEco']) ? filter_var($card['versoEco'], FILTER_SANITIZE_STRING) : null;

        if ($id === false || $rectoEco === null || $versoEco === null) return false;

        return ['id' => $id, 'rectoEco' => $rectoEco , 'versoEco' => $versoEco];
    }
    
    public static function cleanCardGestion($card) {
    
        if (!is_array($card)) return false;

        $id = isset($card['id']) ? filter_var($card['id'], FILTER_VALIDATE_INT) : null;
        $rectoGestion = isset($card['rectoGestion']) ? filter_var($card['rectoGestion'], FILTER_SANITIZE_STRING) : null;
        $versoGestion = isset($card['versoGestion']) ? filter_var($card['versoGestion'], FILTER_SANITIZE_STRING) : null;

        if ($id === false || $rectoGestion === null || $versoGestion === null) return false;

        return ['id' => $id, 'rectoGestion' => $rectoGestion , 'versoGestion' => $versoGestion];
    }

    public static function cleanCardOutilGestion($card) {
    
        if (!is_array($card)) return false;

        $id = isset($card['id']) ? filter_var($card['id'], FILTER_VALIDATE_INT) : null;
        $rectoOutilGestion = isset($card['rectoOutilGestion']) ? filter_var($card['rectoOutilGestion'], FILTER_SANITIZE_STRING) : null;
        $versoOutilGestion = isset($card['versoOutilGestion']) ? filter_var($card['versoOutilGestion'], FILTER_SANITIZE_STRING) : null;

        if ($id === false || $rectoOutilGestion === null || $versoOutilGestion === null) return false;

        return ['id' => $id, 'rectoOutilGestion' => $rectoOutilGestion , 'versoOutilGestion' => $versoOutilGestion];
    }
}
