<?php

namespace App\Tests\Controller;

use App\DataFixtures\AppFixtures;
use App\Entity\User;
use App\Repository\LlamaRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LlamaControllerTest extends WebTestCase
{
    /** @var array */
    private $allUsers;

    /**
     * Страница создания ламы (НЕавторизованный пользователь)
     */
    public function testNewGuest()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/llama/new'
        );

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    /**
     * Страница создания ламы (авторизованный пользователь)
     */
    public function testNewAuthorized()
    {
        $client = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'user-1@localhost',
                'PHP_AUTH_PW' => AppFixtures::DEFAULT_PASSWORD,
            ]
        );
        $client->request(
            'GET',
            '/llama/new'
        );

        $client->submitForm(
            'Сохранить',
            [
                'llama[name]' => 'Test Llama',
                'llama[height]' => '150',
            ]
        );

        // получить все заголовки ответа
        $headers = $client->getResponse()->headers->allPreserveCase();

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals('/llama/', $headers['Location'][0]);
    }

    /**
     * Страница ламы
     */
    public function testShow()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/llama/1'
        );
        $crawler = $client->getCrawler();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('Страница ламы', $crawler->filter('h1')->text());
    }

    public function testEditGuest()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/llama/1/edit'
        );

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testEditRightUserAuthorized()
    {
        $client = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'user-1@localhost',
                'PHP_AUTH_PW' => AppFixtures::DEFAULT_PASSWORD,
            ]
        );

        /**
         * Получить первого пользователя.
         *
         * @var User $user
         */
        $user = $this->getAllUsers()[0];
        $llamaId = $user->getLlamas()->first()->getId();

        $client->request(
            'GET',
            '/llama/' . $llamaId . '/edit'
        );

        $client->submitForm(
            'Сохранить',
            [
                'llama[name]' => 'Test Llama',
                'llama[height]' => '150',
            ]
        );

        // получить все заголовки ответа
        $headers = $client->getResponse()->headers->allPreserveCase();

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals('/llama/', $headers['Location'][0]);
    }

    public function testEditWrongUserAuthorized()
    {
        $client = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'user-1@localhost',
                'PHP_AUTH_PW' => AppFixtures::DEFAULT_PASSWORD,
            ]
        );

        /** @var User $user */
        $user2 = $this->getAllUsers()[1];
        $llamaId = ($user2->getLlamas()->first()->getId());

        $client->request(
            'GET',
            '/llama/' . $llamaId . '/edit'
        );

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testIndexGuest()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/llama/'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testHighestLlamas()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/llama/high'
        );
        $crawler = $client->getCrawler();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(
            'Список высоких лам',
            $crawler->filter('.content h1')->text()
        );
    }

    public function testDeleteGuest()
    {
        $client = static::createClient();

        $user = $this->getAllUsers()[0];
        $llamaId = $user->getLlamas()->first()->getId();

        $client->request(
            'DELETE',
            '/llama/' . $llamaId,
        );

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testDeleteUserAuthorized()
    {
        $client = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'user-1@localhost',
                'PHP_AUTH_PW' => AppFixtures::DEFAULT_PASSWORD,
            ]
        );

        $user2 = $this->getAllUsers()[1];
        $llamaId = ($user2->getLlamas()->first()->getId());

        $client->request(
            'DELETE',
            '/llama/' . $llamaId,
        );

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    // вспомогательные методы

    /**
     * Получить всех пользователей из БД.
     *
     * @return array
     */
    private function getAllUsers()
    {
        if (empty($this->allUsers)) {
            self::bootKernel();
            $userRepository = self::$container->get(UserRepository::class);
            $this->allUsers = $userRepository->findAll();
        }

        return $this->allUsers;
    }

    /**
     * Получить первую ламу пользователя.
     *
     * @param $user
     * @return mixed
     */
    private function getLlamaBelongsUser($user)
    {
        self::bootKernel();
        $llamaRepository = self::$container->get(LlamaRepository::class);
        return $llamaRepository->findOneBy(['owner' => $user]);
    }

}
