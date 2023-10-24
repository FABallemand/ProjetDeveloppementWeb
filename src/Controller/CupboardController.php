<?php

namespace App\Controller;

use App\Entity\Cupboard;
use App\Form\CupboardType;
use App\Repository\CupboardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Cupboard Controller
 */
#[Route('/cupboard')]
class CupboardController extends AbstractController
{
    #[Route('/', name: 'app_cupboard_index', methods: ['GET'])]
    public function index(CupboardRepository $cupboardRepository): Response
    {
        return $this->render('cupboard/index.html.twig', [
            'cupboards' => $cupboardRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cupboard_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cupboard = new Cupboard();
        $form = $this->createForm(CupboardType::class, $cupboard);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cupboard);
            $entityManager->flush();

            $this->addFlash('message', 'Cupboard successfully built!');

            return $this->redirectToRoute('app_cupboard_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cupboard/new.html.twig', [
            'cupboard' => $cupboard,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cupboard_show', methods: ['GET'])]
    public function show(Cupboard $cupboard): Response
    {
        return $this->render('cupboard/show.html.twig', [
            'cupboard' => $cupboard,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cupboard_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cupboard $cupboard, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CupboardType::class, $cupboard);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('message', 'Cupboard successfully repaired!');

            return $this->redirectToRoute('app_cupboard_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cupboard/edit.html.twig', [
            'cupboard' => $cupboard,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cupboard_delete', methods: ['POST'])]
    public function delete(Request $request, Cupboard $cupboard, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cupboard->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cupboard);
            $entityManager->flush();

            $this->addFlash('delete', 'Cupboard successfully destroyed...');
        }

        return $this->redirectToRoute('app_cupboard_index', [], Response::HTTP_SEE_OTHER);
    }
}
