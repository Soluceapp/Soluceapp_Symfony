<?php

namespace App\Tests\Fonctionnel;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WebTestCaseTest extends WebTestCase
{
    public function testUser(): void
    {
        $user = static::createClient();
        $crawler = $user->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }
}
