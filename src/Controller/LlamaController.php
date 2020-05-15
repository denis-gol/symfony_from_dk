<?php

namespace App\Controller;

use App\Entity\Llama;
use App\Entity\User;
use App\Form\LlamaType;
use App\Repository\LlamaRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/llama")
 */
class LlamaController extends AbstractController
{
    /**
     * @Route("/", name="llama_index", methods={"GET"})
     */
    public function index(LlamaRepository $llamaRepository): Response
    {
        return $this->render('llama/index.html.twig', [
            'llamas' => $llamaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="llama_new", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request): Response
    {
        $llama = new Llama();

        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $llama->setOwner($currentUser);

        $form = $this->createForm(LlamaType::class, $llama);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($llama);
            $entityManager->flush();

            return $this->redirectToRoute('llama_index');
        }

        return $this->render('llama/new.html.twig', [
            'llama' => $llama,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/high", name="llama_high", methods={"GET"})
     * @param LlamaRepository $llamaRepository
     * @return Response
     */
    public function highestLlamas(LlamaRepository $llamaRepository)
    {
        $minimumRequireHeight = 150;

        $llamasList = $llamaRepository->getAllHighLlamas($minimumRequireHeight);

        return $this->render(
            'llama/high.html.twig',
            [
                'llamas' => $llamasList,
            ]
        );
    }

    /**
     * @Route("/{id}", name="llama_show", methods={"GET"})
     */
    public function show(Llama $llama): Response
    {
        return $this->render('llama/show.html.twig', [
            'llama' => $llama,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="llama_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Llama $llama): Response
    {

        // проверяем права доступа
        if ($this->getUser()->getId() !== $llama->getOwner()->getId()) {
            throw $this->createAccessDeniedException('You can edit only your llamas');
        }

        $form = $this->createForm(LlamaType::class, $llama);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('llama_index');
        }

        return $this->render('llama/edit.html.twig', [
            'llama' => $llama,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="llama_delete", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, Llama $llama): Response
    {
        if ($this->isCsrfTokenValid('delete'.$llama->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($llama);
            $entityManager->flush();
        }

        return $this->redirectToRoute('llama_index');
    }
}
