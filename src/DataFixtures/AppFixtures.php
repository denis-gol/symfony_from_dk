<?php

namespace App\DataFixtures;

use App\Entity\Llama;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    const DEFAULT_PASSWORD = '12341234';

    /** @var UserPasswordEncoderInterface */
    private UserPasswordEncoderInterface $passwordEncoder;

    /** @var UserRepository */
    private UserRepository $userRepository;

    /** @var Factory|\Faker\Generator */
    private $faker;

    public function __construct(
        UserPasswordEncoderInterface $userPasswordEncoder,
        UserRepository $userRepository
    ) {
        $this->passwordEncoder = $userPasswordEncoder;
        $this->userRepository = $userRepository;
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        // создать пользователей
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setCreatedAt(new \DateTime('now'));

            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    static::DEFAULT_PASSWORD
                )
            );
            $user->setEmail('user-' . $i . '@localhost');
            $manager->persist($user);
        }

        $manager->flush();

        // создать лам
        for ($i = 1; $i <= 10; $i++) {
            $llama = new Llama();

            $user = $this->userRepository->findAll();
            $count = count($user);
            $llama->setOwner($user[rand(0, $count - 1)]);

            $llama->setName($this->faker->name());
            $llama->setHeight($this->faker->numberBetween(100, 200));

            $manager->persist($llama);
        }

        $manager->flush();
    }
}
