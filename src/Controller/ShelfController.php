<?php

namespace App\Controller;

use App\Entity\Shelf;
use App\Entity\Shoe;
use App\Entity\Member;
use App\Form\ShelfType;
use App\Repository\ShelfRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Shelf Controller
 */
#[Route('/shelf')]
class ShelfController extends AbstractController
{
    #[Route('/', name: 'app_shelf_index', methods: ['GET'])]
    public function index(ShelfRepository $shelfRepository): Response
    {
        $member = null;
        $privateShelves = array();
        $publicShelves = $shelfRepository->findBy(['published' => true]);
        if ($this->isGranted('ROLE_ADMIN')) {
            $member = $this->getUser()->getMember();
            $privateShelves = $shelfRepository->findAll();
        } else {
            $user = $this->getUser();
            if ($user) {
                $member = $user->getMember();
                if ($member) {
                    $privateShelves = $shelfRepository->findBy(['member' => $member]);
                }
            }
        }

        return $this->render('shelf/index.html.twig', [
            'member' => $member,
            'private_shelves' => $privateShelves,
            'public_shelves' => $publicShelves,
        ]);
    }

    #[Route('/new', name: 'app_shelf_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
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

            $this->addFlash('message', 'Shelf successfully wall-mounted!');

            return $this->redirectToRoute('app_shelf_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shelf/new.html.twig', [
            'shelf' => $shelf,
            'form' => $form,
        ]);
    }

    #[Route('/new/{id}', name: 'app_shelf_newinmember', methods: ['GET', 'POST'])]
    public function newInMember(Request $request, EntityManagerInterface $entityManager, Member $member): Response
    {
        $shelf = new Shelf();
        $shelf->setMember($member);
        $form = $this->createForm(ShelfType::class, $shelf, ['display_member' => false,]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($shelf);
            $entityManager->flush();

            $this->addFlash('message', 'Shelf successfully wall-mounted!');

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
        $hasAccess = false;
        if ($this->isGranted('ROLE_ADMIN') || $shelf->isPublished()) {
            $hasAccess = true;
        } else {
            $user = $this->getUser();
            if ($user) {
                $member = $user->getMember();
                if ($member &&  ($member == $shelf->getMember())) {
                    $hasAccess = true;
                }
            }
        }
        if (!$hasAccess) {
            throw $this->createAccessDeniedException("You cannot access this shelf!");
        }

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
            throw $this->createNotFoundException("Couldn't find such a shoe in this shelf!");
        }

        $hasAccess = false;
        if ($this->isGranted('ROLE_ADMIN') || $shelf->isPublished()) {
            $hasAccess = true;
        } else {
            $user = $this->getUser();
            if ($user) {
                $member = $user->getMember();
                if ($member &&  ($member == $shelf->getMember())) {
                    $hasAccess = true;
                }
            }
        }
        if (!$hasAccess) {
            throw $this->createAccessDeniedException("You cannot access this shoe in this shelf!");
        }

        return $this->render('shelf/shoe_show.html.twig', [
            'shoe' => $shoe,
            'shelf' => $shelf
        ]);
    }

    /**
     * Mark shelf in the user's session
     */
    #[Route('/mark/{id}', name: 'app_shelf_mark', requirements: ['id' => '\d+'], methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function markAction(Request $request, Shelf $shelf): Response
    {
        // Retrieve marked shoes array from session
        $markedShelves = $request->getSession()->get('marked_shelves');
        dump($markedShelves);
        if (!is_array($markedShelves)) {
            $markedShelves = array();
        }

        // dump($shelf);
        // dump($markedShelves);

        $id = $shelf->getId();
        // if the shoe id is not in the array of marked shoes, add it to the array
        if (!in_array($id, $markedShelves)) {
            $markedShelves[] = $id;
        } else
        // else, remove it from the array
        {
            // substract two arrays
            $markedShelves = array_diff($markedShelves, array($id));
        }

        // Update marked shoes array in session
        $request->getSession()->set('marked_shelves', $markedShelves);

        return $this->redirectToRoute(
            'app_shelf_show',
            ['id' => $shelf->getId()]
        );
    }

    #[Route('/{id}/edit', name: 'app_shelf_edit', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function edit(Request $request, Shelf $shelf, EntityManagerInterface $entityManager): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($this->getUser()->getMember() == $shelf->getMember());
        if (!$hasAccess) {
            throw $this->createAccessDeniedException("You cannot edit another member's shelf!");
        }

        $form = $this->createForm(ShelfType::class, $shelf);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shelf->setUpdated(new \DateTime());
            $entityManager->flush();

            $this->addFlash('message', 'Shelf successfully straightened!');

            return $this->redirectToRoute('app_shelf_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shelf/edit.html.twig', [
            'shelf' => $shelf,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_shelf_delete', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function delete(Request $request, Shelf $shelf, EntityManagerInterface $entityManager): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($this->getUser()->getMember() == $shelf->getMember());
        if (!$hasAccess) {
            throw $this->createAccessDeniedException("You cannot delete another member's shelf!");
        }

        if ($this->isCsrfTokenValid('delete' . $shelf->getId(), $request->request->get('_token'))) {
            $entityManager->remove($shelf);
            $entityManager->flush();

            $this->addFlash('delete', 'Shelf successfully removed from the wall...');
        }

        return $this->redirectToRoute('app_shelf_index', [], Response::HTTP_SEE_OTHER);
    }
}
