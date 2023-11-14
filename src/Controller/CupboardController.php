<?php

namespace App\Controller;

use App\Entity\Cupboard;
use App\Entity\Shoe;
use App\Entity\Member;
use App\Form\CupboardType;
use App\Repository\CupboardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Cupboard Controller
 */
#[Route('/cupboard')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class CupboardController extends AbstractController
{
    #[Route('/', name: 'app_cupboard_index', methods: ['GET'])]
    public function index(CupboardRepository $cupboardRepository): Response
    {
        $cupboards = array();
        $user = $this->getUser();
        $member = null;
        if ($user) {
            $member = $user->getMember();
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            $cupboards = $cupboardRepository->findAll();
        } else {
            $cupboards = $cupboardRepository->findBy(['member' => $member]);
        }

        return $this->render('cupboard/index.html.twig', [
            'cupboards' => $cupboards,
            'member' => $member,
        ]);
    }

    #[Route('/new', name: 'app_cupboard_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cupboard = new Cupboard();
        $form = $this->createForm(CupboardType::class, $cupboard, ['display_shoes' => true,]);
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

    #[Route('/new/{id}', name: 'app_cupboard_newinmember', methods: ['GET', 'POST'])]
    public function newInMember(Request $request, EntityManagerInterface $entityManager, Member $member): Response
    {
        $user = $this->getUser();
        $current_member = null;
        if ($user) {
            $current_member = $user->getMember();
        }
        if ($current_member != $member) {
            throw $this->createAccessDeniedException("You cannot build a cupboard for another member!");
        }

        $cupboard = new Cupboard();
        $cupboard->setMember($member);
        $form = $this->createForm(CupboardType::class, $cupboard, ['display_member' => false, 'display_shoes' => true,]);
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
        $user = $this->getUser();
        $member = null;
        if ($user) {
            $member = $user->getMember();
        }
        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($member == $cupboard->getMember());
        if (!$hasAccess) {
            throw $this->createAccessDeniedException("You cannot access another member's cupboard!");
        }

        return $this->render('cupboard/show.html.twig', [
            'cupboard' => $cupboard,
        ]);
    }

    // #[Route('/{id}/edit', name: 'app_cupboard_edit', methods: ['GET', 'POST'])]
    // #[IsGranted('ROLE_USER')]
    // public function edit(Request $request, Cupboard $cupboard, EntityManagerInterface $entityManager): Response
    // {
    //     $hasAccess = $this->isGranted('ROLE_ADMIN') || ($this->getUser()->getMember() == $cupboard->getMember());
    //     if (!$hasAccess) {
    //         throw $this->createAccessDeniedException("You cannot edit another member's cupboard!");
    //     }

    //     $form = $this->createForm(CupboardType::class, $cupboard, ['display_member' => false, 'display_shoes' => true,]);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         $this->addFlash('message', 'Cupboard successfully repaired!');

    //         return $this->redirectToRoute('app_cupboard_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('cupboard/edit.html.twig', [
    //         'cupboard' => $cupboard,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{id}/edit', name: 'app_cupboard_edit', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function edit(Request $request, Cupboard $cupboard, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $member = null;
        if ($user) {
            $member = $user->getMember();
        }

        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($member == $cupboard->getMember());
        if (!$hasAccess) {
            throw $this->createAccessDeniedException("You cannot edit another member's cupboard!");
        }

        // Save shoes before editing
        $originalShoes = new ArrayCollection();
        foreach ($cupboard->getShoes() as $shoe) {
            $originalShoes->add($shoe);
        }

        $form = $this->createForm(CupboardType::class, $cupboard, ['display_member' => false, 'display_shoes' => true,]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Update shoes in cupboard
            foreach ($originalShoes as $shoe) {
                if (false === $cupboard->getShoes()->contains($shoe)) {
                    // This shoe has been removed, so it must be removed manualy
                    $cupboard->getShoes()->removeElement($shoe);
                    $entityManager->persist($shoe);
                    $entityManager->remove($shoe);
                }
            }
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
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function delete(Request $request, Cupboard $cupboard, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $member = null;
        if ($user) {
            $member = $user->getMember();
        }
        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($member == $cupboard->getMember());
        if (!$hasAccess) {
            throw $this->createAccessDeniedException("You cannot delete another member's cupboard!");
        }

        if ($this->isCsrfTokenValid('delete' . $cupboard->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cupboard);
            $entityManager->flush();

            $this->addFlash('delete', 'Cupboard successfully destroyed...');
        }

        return $this->redirectToRoute('app_cupboard_index', [], Response::HTTP_SEE_OTHER);
    }
}
