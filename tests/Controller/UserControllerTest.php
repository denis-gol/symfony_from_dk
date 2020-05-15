<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testList()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/user/list' // роут, а не именованный роут!
        );
        $crawler = $client->getCrawler();
        $this->assertCount(
            2,
            $crawler->filter('li')
        );
    }

    public function testShowUserPage()
    {
    }
}
