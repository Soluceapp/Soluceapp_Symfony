<?php

namespace App\DataFixtures;

use App\Entity\Scenario;
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

        for ($i=0; $i<100; $i++)
        {
            $scenario = new Scenario();
            $scenario->setNbscenario($i);
            $scenario->setQuestion1('Q1');
            $scenario->setQuestion2('Q2');
            $scenario->setQuestion3('Q3');
            $scenario->setQuestion4('Q4');
            $scenario->setQuestion5('Q5');
            $scenario->setQuestion6('Q6');
            $scenario->setReponse1(['R1','R2','R3','R4']);
            $scenario->setReponse2(['R1','R2','R3','R4']);
            $scenario->setReponse3(['R1','R2','R3','R4']);
            $scenario->setReponse4(['R1','R2','R3','R4']);
            $scenario->setReponse5(['R1','R2','R3','R4']);
            $scenario->setReponse6(['R1','R2','R3','R4']);
            $scenario->setReponsemotcroise('Rmot');
            $scenario->setSolution1(1);
            $scenario->setSolution2(2);
            $scenario->setSolution3(3);
            $scenario->setSolution4(4);
            $scenario->setSolution5(5);
            $scenario->setSolution6(6);
            $scenario->setSolution6(6);
            $scenario->setLienimage('2-1.jpg');
            $scenario->setLienmotcroise('https://learningapps.org/watch?v=pexjosz3k20');
            $scenario->setLienchevaux('https://learningapps.org/watch?v=poedzn5n521');


            $manager ->persist($scenario);
        }

        $manager->flush();
    }
}
