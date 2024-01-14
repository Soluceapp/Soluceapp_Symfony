<?php

namespace App\Tests\Unitaire;

use App\Entity\Scenario;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class KernerlCaseTest extends KernelTestCase
{
    public function testEnvironnement(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());   
    }

    public function testScenario(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $scenario = new Scenario();
        $scenario->setNameScenario('Test scenario')
                ->setNbscenario(1);

        $errors = $container->get('validator')->validate($scenario);

        $this->assertCount(0, $errors);
    }



}
