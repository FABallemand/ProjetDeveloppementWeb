<?php

namespace App\Controller;

use App\Entity\Shoe;
use App\Entity\Cupboard;
use App\Form\ShoeType;
use App\Repository\ShoeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
            // Change content-type according to image's
            $imagefile = $shoe->getImageFile();
            if ($imagefile) {
                $mimetype = $imagefile->getMimeType();
                $shoe->setContentType($mimetype);
            }
            $entityManager->persist($shoe);
            $entityManager->flush();

            $this->addFlash('message', 'Shoe successfully bought!');

            return $this->redirectToRoute('app_shoe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shoe/new.html.twig', [
            'shoe' => $shoe,
            'form' => $form,
        ]);
    }

    #[Route('/newincupboard/{id}', name: 'app_shoe_newincupboard', methods: ['GET', 'POST'])]
    public function newInCupboard(Request $request, EntityManagerInterface $entityManager, Cupboard $cupboard): Response
    {
        $shoe = new Shoe();
        // already set a cupboard, no need to add that field in the form (in ShoeType)
        $shoe->setCupboard($cupboard);

        $form = $this->createForm(ShoeType::class, $shoe, ['display_cupboard' => false,]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Change content-type according to image's
            $imagefile = $shoe->getImageFile();
            if ($imagefile) {
                $mimetype = $imagefile->getMimeType();
                $shoe->setContentType($mimetype);
            }
            $entityManager->persist($shoe);
            $entityManager->flush();

            $this->addFlash('message', 'Shoe successfully stored inside cupboard!');

            return $this->redirectToRoute('app_cupboard_show', ['id' => $cupboard->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shoe/newincupboard.html.twig', [
            'cupboard' => $cupboard,
            'shoe' => $shoe,
            'form' => $form->createView(),
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
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Shoe $shoe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ShoeType::class, $shoe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('message', 'Shoe successfully cleaned!');

            return $this->redirectToRoute('app_shoe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shoe/edit.html.twig', [
            'shoe' => $shoe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_shoe_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Shoe $shoe, EntityManagerInterface $entityManager): Response
    {
        // if ($shoe->isCompleted() == false) {
        //     $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // }

        if ($this->isCsrfTokenValid('delete' . $shoe->getId(), $request->request->get('_token'))) {
            $entityManager->remove($shoe);
            $entityManager->flush();

            $this->addFlash('delete', 'Shoe successfully thrown...');
        }

        return $this->redirectToRoute('app_shoe_index', [], Response::HTTP_SEE_OTHER);
    }
}
