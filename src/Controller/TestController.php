<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route(
     *     "/test",
     *     name="test",
     *     methods={"GET"}
     * )
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function test()
    {
        $date = date('d.m.Y');

        return $this->render(
            'test/test.twig',
            [
                'date' => $date,
            ]
        );
    }

}