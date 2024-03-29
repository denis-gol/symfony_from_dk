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
     * @Route("/{id}", name="item", methods={"GET"})
     * @param User $id в запросе должен быть user_id
     * @param UserRepository $userRepository
     * @return Response
     */
    public function showUserPage(User $id, UserRepository $userRepository)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if (empty($currentUser) || $currentUser->getId() !== $id->getId()) {
            throw $this->createAccessDeniedException('Wrong user!');
        }

        return $this->render(
            'user/user.twig',
            [
                'user' => $id,
            ]
        );
    }
}
