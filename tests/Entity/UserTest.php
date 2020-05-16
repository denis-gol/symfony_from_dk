<?php

namespace App\Tests\Entity;

use App\DataFixtures\AppFixtures;
use App\Entity\Llama;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @return User Empty Entity User
     */
    public function testCreateEmptyUser(): User
    {
        $user = new User();

        $this->assertEquals(TRUE, $user instanceof User);

        return $user;
    }

    /**
     * @depends testCreateEmptyUser
     * @var User $user
     */
    public function testSettersGettersUser($user)
    {
        $testEmail = 'test@test.test';
        $testRoles = 'ROLE_TEST';
        $testPassword = AppFixtures::DEFAULT_PASSWORD;
        $testCreatedAt = new \DateTime('now');

        $user->setEmail($testEmail);
        $user->setRoles([$testRoles]);
        $user->setPassword($testPassword);
        $user->setCreatedAt($testCreatedAt);

        $this->assertSame(null, $user->getId());
        $this->assertEquals($testEmail, $user->getEmail());
        $this->assertEquals($testEmail, $user->getUsername());
        $this->assertEquals([$testRoles, 'ROLE_USER'], $user->getRoles());
        $this->assertEquals($testPassword, $user->getPassword());
        $this->assertEquals($testCreatedAt, $user->getCreatedAt());
    }

    /**
     * @return Llama
     */
    public function testCreateLlama()
    {
        $llama = new Llama();

        $this->assertEquals(TRUE, $llama instanceof Llama);

        return $llama;
    }

    /**
     * @depends testCreateEmptyUser
     * @depends testCreateLlama
     * @var User $user
     * @var Llama $llama
     */
    public function testAddGetLlama($user, $llama)
    {
        $user->addLlama($llama);

        $this->assertEquals(true, $user->getLlamas() instanceof Collection);
        $this->assertNotEmpty($user->getLlamas());
    }

    /**
     * @depends testCreateEmptyUser
     * @depends testCreateLlama
     * @var User $user
     * @var Llama $llama
     */
    public function testRemoveLlama($user, $llama)
    {
        $user->addLlama($llama);
        $user->removeLlama($llama);

        $this->assertEmpty($user->getLlamas());
    }

    /**
     * @depends testCreateEmptyUser
     * @param $user
     */
    public function testToString($user)
    {
        $user->setEmail('test');
        $toString = (string) $user;

        $this->assertEquals(': test', $toString);
    }

}
