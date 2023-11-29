<?php

namespace App\DataFixtures;

use App\Entity\Registre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

//require_once 'vendor/autoload.php';

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
       //$faker = Factory\Factory::create('fr_FR');    $faker->name()
/*
        for ($i=1; $i < 50; ++$i) { 
            $registre = new Registre();
            $registre->setDutil('Elève '.$i);
            $registre->setDmp(mt_rand(100000000000,999999999999));
            $registre->setPoints(0);
            $registre->setClasse('TAGORA');
            $registre->setType('user');
            $registre->setCat(1);
            $manager->persist($registre);
        }
            
      */  
        $manager->flush();
    }
}
