<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @Route("/user", name="user_")
 * @package App\Controller
 */
class UserController extends AbstractController
{
    /**
     * @Route("/list", name="list", methods={"GET"})
     * @param UserRepository $userRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        return $this->render(
            'user/list.twig',
            [
                'users' => $users,
            ]
        );
    }
}
