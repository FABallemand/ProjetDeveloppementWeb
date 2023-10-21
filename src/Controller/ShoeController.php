<?php

namespace App\Controller;

use App\Entity\Shoe;
use App\Form\ShoeType;
use App\Repository\ShoeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Shoe Controller
 */
#[Route('/shoe')]
class ShoeController extends AbstractController
{

    #[Route('/', name: 'app_shoe_index', methods: ['GET'])]
    public function index(ShoeRepository $shoeRepository): Response
    {
        return $this->render('shoe/index.html.twig', [
            'shoes' => $shoeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_shoe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $shoe = new Shoe();
        $form = $this->createForm(ShoeType::class, $shoe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($shoe);
            $entityManager->flush();

            return $this->redirectToRoute('app_shoe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shoe/new.html.twig', [
            'shoe' => $shoe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_shoe_show', methods: ['GET'])]
    public function show(Shoe $shoe): Response
    {
        return $this->render('shoe/show.html.twig', [
            'shoe' => $shoe,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_shoe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Shoe $shoe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ShoeType::class, $shoe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_shoe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shoe/edit.html.twig', [
            'shoe' => $shoe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_shoe_delete', methods: ['POST'])]
    public function delete(Request $request, Shoe $shoe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$shoe->getId(), $request->request->get('_token'))) {
            $entityManager->remove($shoe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_shoe_index', [], Response::HTTP_SEE_OTHER);
    }
}
