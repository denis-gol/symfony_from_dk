<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\DataFixtures\AppFixtures;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testListGuest()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/user/list' // роут, а не именованный роут!
        );

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

//        $crawler = $client->getCrawler();
//        $this->assertCount(
//            10,
//            $crawler->filter('.content li')
//        );
    }

    public function testShowUserPageGuest()
    {
        $client = static::createClient();

        // грузим ядро
        self::bootKernel();
        $userRepository = self::$container->get(UserRepository::class);
        $user = $userRepository->findOneBy([]);

        $client->request(
            'GET',
            '/user/' . $user->getId(), // роут, а не именованный роут!
        );

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testListAuthorized()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'user-1@localhost',
            'PHP_AUTH_PW' => AppFixtures::DEFAULT_PASSWORD,
        ]);

        $client->request(
            'GET',
            '/user/list' // роут, а не именованный роут!
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }
}
