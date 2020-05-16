<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRepositoryTest extends WebTestCase
{

    public function testUpgradePassword()
    {
        // ошибка в методе репозитория при установке нового пароля
        $this->markTestIncomplete();
    }
}
