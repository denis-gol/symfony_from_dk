<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
     * @return Response
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

    /**
     * @Route("/{user}", name="item", methods={"GET"})
     * @param User $user в запросе должен быть user_id
     * @param UserRepository $userRepository
     * @return Response
     */
    public function showUserPage(User $user, UserRepository $userRepository)
    {
        return $this->render(
            'user/user.twig',
            [
                'user' => $user,
                'urlNameList' => $this->generateUrl('user_list'),
            ]
        );
    }
}
