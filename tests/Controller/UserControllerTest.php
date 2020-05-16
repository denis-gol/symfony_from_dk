<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\DataFixtures\AppFixtures;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    /**
     * Test NonAuthorized Access
     */
    public function testListGuest()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/user/list'
        );

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

//        $crawler = $client->getCrawler();
//        $this->assertCount(
//            10,
//            $crawler->filter('.content li')
//        );
    }

    /**
     * Test NonAuthorized Access
     */
    public function testShowUserPageGuest()
    {
        $client = static::createClient();

        // грузим ядро
        self::bootKernel();
        $userRepository = self::$container->get(UserRepository::class);
        $user = $userRepository->findOneBy([]);

        $crawler = $client->request(
            'GET',
            '/user/' . $user->getId(), // роут, а не именованный роут!
        );

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    /**
     * Test Authorized Access
     */
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

    /**
     * Test Authorized Access
     */
    public function testUserAuthorized()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'user-1@localhost',
            'PHP_AUTH_PW' => AppFixtures::DEFAULT_PASSWORD,
        ]);

        // грузим ядро
        self::bootKernel();
        $userRepository = self::$container->get(UserRepository::class);
        $user = $userRepository->findOneBy([]);

        $client->request(
            'GET',
            '/user/' . $user->getId(), // роут, а не именованный роут!
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

    /**
     * Test Wrong Authorized Access
     */
    public function testUserWrongAuthorized()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'user-1@localhost',
            'PHP_AUTH_PW' => AppFixtures::DEFAULT_PASSWORD,
        ]);

        // грузим ядро
        self::bootKernel();
        $userRepository = self::$container->get(UserRepository::class);
        $user = $userRepository->findOneBy([]);

        $client->request(
            'GET',
            '/user/' . ($user->getId() + 1), // роут, а не именованный роут!
        );

        $this->assertEquals(403, $client->getResponse()->getStatusCode());

    }
}
