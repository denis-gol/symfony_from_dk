<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Validation;

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
     *     "/test/{count}",
     *     name="showDatesList",
     *     methods={"GET"}
     * )
     * @param int $count
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showDatesList(int $count)
    {
        $date = date('d.m.Y');

        $validator = Validation::createValidator();
        $errors = $validator->validate(
            $count,
            [
                new Type('int'),
                new Range(['min' => 1, 'max' => 100]),
                new NotBlank(),
            ]
        );

        if (count($errors) > 0) {
            throw $this->createNotFoundException('');
        }

        return $this->render(
            'test/test_n.twig',
            [
                'date' => $date,
                'count' => $count,
            ]
        );
    }

}