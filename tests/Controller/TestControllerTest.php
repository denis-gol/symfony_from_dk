<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TestControllerTest extends WebTestCase
{

    public function testTest(): void
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/test' // роут, а не именованный роут!
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // можно получить содержимое страницы (элемент?)
        $crawler = $client->getCrawler();
        $this->assertEquals(
            'Test page',
            $crawler
                ->filter('h1')
                ->text()
        );

        $this->assertContains(
            date('d.m.Y'),
            $client->getResponse()->getContent()
        );
    }


}