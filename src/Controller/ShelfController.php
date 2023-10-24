<?php

namespace App\Controller;

use App\Entity\Shelf;
use App\Entity\Shoe;
use App\Form\ShelfType;
use App\Repository\ShelfRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Shelf Controller
 */
#[Route('/shelf')]
class ShelfController extends AbstractController
{
    #[Route('/', name: 'app_shelf_index', methods: ['GET'])]
    public function index(ShelfRepository $shelfRepository): Response
    {
        return $this->render('shelf/index.html.twig', [
            'shelves' => $shelfRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_shelf_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $shelf = new Shelf();
        $form = $this->createForm(ShelfType::class, $shelf, ['shelf_is_new' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shelf->setCreated(new \DateTime());
            $shelf->setUpdated(new \DateTime());
            $entityManager->persist($shelf);
            $entityManager->flush();

            return $this->redirectToRoute('app_shelf_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shelf/new.html.twig', [
            'shelf' => $shelf,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_shelf_show', methods: ['GET'])]
    public function show(Shelf $shelf): Response
    {
        return $this->render('shelf/show.html.twig', [
            'shelf' => $shelf,
        ]);
    }

    #[Route('/{shelf_id}/shoe/{shoe_id}', name: 'app_shelf_shoe_show', methods: ['GET'])]
    #[ParamConverter('shelf', options: ['mapping' => ['shelf_id' => 'id']])]
    #[ParamConverter('shoe', options: ['mapping' => ['shoe_id' => 'id']])]
    public function shoeShow(Shelf $shelf, Shoe $shoe): Response
    {
        if (!$shelf->getShoes()->contains($shoe)) {
            dump($shelf);
            dump($shoe);
            throw $this->createNotFoundException("Couldn't find such a shoe in this shelf!");
        }

        if (!$shelf->isPublished()) {
            throw $this->createAccessDeniedException("You cannot access the requested ressource!");
        }

        return $this->render('shelf/shoe_show.html.twig', [
            'shoe' => $shoe,
            'shelf' => $shelf
        ]);
    }

    #[Route('/{id}/edit', name: 'app_shelf_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Shelf $shelf, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ShelfType::class, $shelf);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shelf->setUpdated(new \DateTime());
            $entityManager->flush();

            return $this->redirectToRoute('app_shelf_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shelf/edit.html.twig', [
            'shelf' => $shelf,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_shelf_delete', methods: ['POST'])]
    public function delete(Request $request, Shelf $shelf, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $shelf->getId(), $request->request->get('_token'))) {
            $entityManager->remove($shelf);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_shelf_index', [], Response::HTTP_SEE_OTHER);
    }
}
