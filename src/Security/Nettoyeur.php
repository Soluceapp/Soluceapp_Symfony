<?php

namespace App\Security;
use App\Controller\ResultatController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Nettoyeur extends AbstractController 
{

    public function nettoyeur_str($variarecup): string
    {
    $symboles ='/[^-a-z0-9 \\éèàïîùçê,.]+/i';
    $variarecup = (string)preg_replace($symboles,'',$variarecup); 
    return $variarecup;
    }
    public function nettoyeur_int($variarecup): int
    {
    $symboles ='/[^0-9\\=]+/i';
    $variarecup = (int)preg_replace($symboles,'',$variarecup); 
    return $variarecup;
    }
}
