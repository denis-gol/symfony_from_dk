<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    const DEFAULT_PASSWORD = '12341234';

    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $passwordEncoder;


    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->passwordEncoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        for ($i = 1; $i <=10; $i++) {
            $user = new User();
            $user->setCreatedAt(new \DateTime('now'));

            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                static::DEFAULT_PASSWORD
            ));
            $user->setEmail('user-' . $i . '@localhost');
            $manager->persist($user);

        }

        $manager->flush();
    }
}
