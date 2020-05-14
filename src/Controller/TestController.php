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

    /**
     * @Route(
     *     "/tests/{count}",
     *     name="showDateList",
     *     methods={"GET"}
     * )
     * @param int $count
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showDateList(int $count)
    {
        $date = date('d.m.Y');

        $validator = $this->get('validator');
        $errors = $validator->validate($count);

        if (count($errors) > 0) {
            return $this->render('test/errors.twig', array(
                'errors' => $errors,
            ));
        }

        return $this->render(
            'test/test_n.twig',
            [
                'date' => $date,
                'count' => $count,
                'errors' => $errors,
            ]
        );
    }

}